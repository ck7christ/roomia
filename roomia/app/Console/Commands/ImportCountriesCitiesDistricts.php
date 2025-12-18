<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use PhpOffice\PhpSpreadsheet\IOFactory;

class ImportCountriesCitiesDistricts extends Command
{
    protected $signature = 'import:locations
        {--countries= : Path file countries (.xls/.xlsx)}
        {--locations= : Path file TinhHuyenXa2021 (.xlsx)}
        {--vn=VN : Mã quốc gia Việt Nam (VN)}';

    protected $description = 'Import countries, cities, districts từ Excel';

    public function handle(): int
    {
        $countriesFile = $this->option('countries') ?: storage_path('app/import/countries.xls');
        $locationsFile = $this->option('locations') ?: storage_path('app/import/TinhHuyenXa2021.xlsx');
        $vnCode = strtoupper((string) ($this->option('vn') ?: 'VN'));

        foreach (['countries', 'cities', 'districts'] as $t) {
            if (!Schema::hasTable($t)) {
                $this->error("Thiếu bảng '{$t}'. Bạn cần migrate tạo bảng trước.");
                return self::FAILURE;
            }
        }

        if (!file_exists($countriesFile)) {
            $this->error("Không thấy file countries: {$countriesFile}");
            return self::FAILURE;
        }
        if (!file_exists($locationsFile)) {
            $this->error("Không thấy file locations: {$locationsFile}");
            return self::FAILURE;
        }

        $colsCountries = Schema::getColumnListing('countries');
        $colsCities = Schema::getColumnListing('cities');
        $colsDistricts = Schema::getColumnListing('districts');

        $countryCodeCol = $this->firstExisting($colsCountries, ['code', 'iso2', 'alpha2', 'country_code']);
        $countryNameCol = $this->firstExisting($colsCountries, ['name', 'country_name', 'title']);
        if (!$countryNameCol) {
            $this->error("Bảng countries thiếu cột tên (vd: name/country_name).");
            return self::FAILURE;
        }

        $cityCodeCol = $this->firstExisting($colsCities, ['code', 'city_code', 'province_code']);
        $cityNameCol = $this->firstExisting($colsCities, ['name', 'city_name', 'province_name']);
        $cityCountryIdCol = $this->firstExisting($colsCities, ['country_id']);
        $cityCountryCodeCol = $this->firstExisting($colsCities, ['country_code']);

        if (!$cityNameCol) {
            $this->error("Bảng cities thiếu cột tên (vd: name/city_name).");
            return self::FAILURE;
        }

        $districtCodeCol = $this->firstExisting($colsDistricts, ['code', 'district_code']);
        $districtNameCol = $this->firstExisting($colsDistricts, ['name', 'district_name']);
        $districtCityIdCol = $this->firstExisting($colsDistricts, ['city_id']);
        $districtCityCodeCol = $this->firstExisting($colsDistricts, ['city_code']);

        if (!$districtNameCol) {
            $this->error("Bảng districts thiếu cột tên (vd: name/district_name).");
            return self::FAILURE;
        }

        DB::disableQueryLog();

        DB::transaction(function () use ($countriesFile, $locationsFile, $vnCode, $colsCountries, $colsCities, $colsDistricts, $countryCodeCol, $countryNameCol, $cityCodeCol, $cityNameCol, $cityCountryIdCol, $cityCountryCodeCol, $districtCodeCol, $districtNameCol, $districtCityIdCol, $districtCityCodeCol) {
            // ========= 1) COUNTRIES =========
            $countryRows = $this->readCountries($countriesFile, $countryNameCol, $countryCodeCol, $colsCountries);

            if ($countryCodeCol) {
                DB::table('countries')->upsert(
                    $countryRows,
                    [$countryCodeCol],
                    [$countryNameCol, ...$this->updatableTimestamps($colsCountries)]
                );
            } else {
                // fallback nếu không có code/iso2: updateOrInsert theo tên
                foreach ($countryRows as $r) {
                    DB::table('countries')->updateOrInsert(
                        [$countryNameCol => $r[$countryNameCol]],
                        $r
                    );
                }
            }

            // Lấy VN id
            $vnId = null;
            if ($countryCodeCol) {
                $vnId = DB::table('countries')->where($countryCodeCol, $vnCode)->value('id');
            }
            if (!$vnId) {
                // fallback tìm theo tên
                $vnId = DB::table('countries')
                    ->where($countryNameCol, 'like', '%VIET%NAM%')
                    ->orWhere($countryNameCol, 'like', '%VIỆT%NAM%')
                    ->value('id');
            }
            if (!$vnId) {
                // nếu vẫn chưa có thì tạo VN
                $insert = [$countryNameCol => 'VIET NAM'];
                if ($countryCodeCol)
                    $insert[$countryCodeCol] = $vnCode;
                $insert = $this->withTimestamps('countries', $insert, $colsCountries);
                $vnId = DB::table('countries')->insertGetId($insert);
            }

            // ========= 2) CITIES + DISTRICTS =========
            [$cities, $districtsRaw] = $this->readCitiesDistricts($locationsFile);

            // Build city rows theo schema thực tế
            $cityRows = [];
            foreach ($cities as $code => $name) {
                $row = [$cityNameCol => $name];

                if ($cityCodeCol)
                    $row[$cityCodeCol] = $code;
                if ($cityCountryIdCol)
                    $row[$cityCountryIdCol] = $vnId;
                if ($cityCountryCodeCol)
                    $row[$cityCountryCodeCol] = $vnCode;

                $row = $this->withTimestamps('cities', $row, $colsCities);
                $cityRows[] = $row;
            }

            // Upsert cities
            if ($cityCodeCol) {
                DB::table('cities')->upsert(
                    $cityRows,
                    [$cityCodeCol],
                    [$cityNameCol, ...$this->updatableTimestamps($colsCities)]
                );
            } else {
                foreach ($cityRows as $r) {
                    DB::table('cities')->updateOrInsert(
                        [$cityNameCol => $r[$cityNameCol]],
                        $r
                    );
                }
            }

            // Map city_code -> city_id (ưu tiên code nếu có)
            $cityIdByKey = collect();
            if ($cityCodeCol) {
                $cityIdByKey = DB::table('cities')->pluck('id', $cityCodeCol);
            } else {
                $cityIdByKey = DB::table('cities')->pluck('id', $cityNameCol);
            }

            // Build district rows
            $districtRows = [];
            foreach ($districtsRaw as $d) {
                $row = [$districtNameCol => $d['name']];

                if ($districtCodeCol)
                    $row[$districtCodeCol] = $d['code'];

                if ($districtCityIdCol) {
                    $key = $cityCodeCol ? $d['city_code'] : $d['city_name'];
                    $cityId = $cityIdByKey[$key] ?? null;
                    if ($cityId)
                        $row[$districtCityIdCol] = $cityId;
                }

                if ($districtCityCodeCol) {
                    $row[$districtCityCodeCol] = $d['city_code'];
                }

                $row = $this->withTimestamps('districts', $row, $colsDistricts);
                $districtRows[] = $row;
            }

            // Upsert districts
            if ($districtCodeCol) {
                DB::table('districts')->upsert(
                    $districtRows,
                    [$districtCodeCol],
                    [$districtNameCol, ...$this->updatableTimestamps($colsDistricts)]
                );
            } else {
                foreach ($districtRows as $r) {
                    DB::table('districts')->updateOrInsert(
                        [$districtNameCol => $r[$districtNameCol]],
                        $r
                    );
                }
            }
        });

        $this->info("OK: import xong countries + cities + districts");
        return self::SUCCESS;
    }

    // ===== Helpers =====

    private function firstExisting(array $columns, array $candidates): ?string
    {
        $set = array_flip($columns);
        foreach ($candidates as $c) {
            if (isset($set[$c]))
                return $c;
        }
        return null;
    }

    private function withTimestamps(string $table, array $row, array $cols): array
    {
        $now = now();
        if (in_array('created_at', $cols, true) && !isset($row['created_at']))
            $row['created_at'] = $now;
        if (in_array('updated_at', $cols, true))
            $row['updated_at'] = $now;
        return $row;
    }

    private function updatableTimestamps(array $cols): array
    {
        $u = [];
        if (in_array('updated_at', $cols, true))
            $u[] = 'updated_at';
        return $u;
    }

    private function normHeader($s): string
    {
        $s = trim((string) $s);
        $s = mb_strtolower($s);
        $ascii = @iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $s);
        if ($ascii !== false)
            $s = $ascii;
        $s = preg_replace('/[^a-z0-9]+/i', '', $s);
        return $s ?: '';
    }

    private function findColIndex(array $headerRow, array $needles, string $fallbackLetter): string
    {
        // $headerRow từ PhpSpreadsheet dạng ['A' => '...', 'B' => '...']
        $needlesNorm = array_map(fn($x) => $this->normHeader($x), $needles);

        foreach ($headerRow as $col => $val) {
            $n = $this->normHeader($val);
            foreach ($needlesNorm as $needle) {
                if ($needle !== '' && str_contains($n, $needle))
                    return $col;
            }
        }
        return $fallbackLetter;
    }

    private function readCountries(string $file, string $nameCol, ?string $codeCol, array $colsCountries): array
    {
        $sheet = IOFactory::load($file)->getActiveSheet();
        $rows = $sheet->toArray(null, true, true, true); // keyed by letters
        $header = array_shift($rows) ?: [];

        // File của bạn thường: A=STT, B=Tên quốc gia, C=Tên viết tắt
        $colName = $this->findColIndex($header, ['ten quoc gia', 'country', 'quoc gia'], 'B');
        $colCode = $this->findColIndex($header, ['ten viet tat', 'viet tat', 'abbr', 'code'], 'C');

        $out = [];
        foreach ($rows as $r) {
            $name = trim((string) ($r[$colName] ?? ''));
            $code = trim((string) ($r[$colCode] ?? ''));

            if ($name === '')
                continue;

            $row = [$nameCol => $name];
            if ($codeCol && $code !== '')
                $row[$codeCol] = strtoupper($code);

            $row = $this->withTimestamps('countries', $row, $colsCountries);
            $out[] = $row;
        }
        return $out;
    }

    private function readCitiesDistricts(string $file): array
    {
        $sheet = IOFactory::load($file)->getActiveSheet();
        $rows = $sheet->toArray(null, true, true, true);
        $header = array_shift($rows) ?: [];

        $cCityName = $this->findColIndex($header, ['tinh thanh pho'], 'A');
        $cCityCode = $this->findColIndex($header, ['ma tp'], 'B');
        $cDistName = $this->findColIndex($header, ['quan huyen'], 'C');
        $cDistCode = $this->findColIndex($header, ['ma qh'], 'D');

        $cities = [];          // city_code => city_name
        $districtsRaw = [];    // list

        foreach ($rows as $r) {
            $cityName = trim((string) ($r[$cCityName] ?? ''));
            $cityCodeVal = $r[$cCityCode] ?? null;

            $distName = trim((string) ($r[$cDistName] ?? ''));
            $distCodeVal = $r[$cDistCode] ?? null;

            if ($cityName === '' || $cityCodeVal === null)
                continue;
            if ($distName === '' || $distCodeVal === null)
                continue;

            $cityCode = (string) intval($cityCodeVal);
            $distCode = (string) intval($distCodeVal);

            $cities[$cityCode] = $cityName;

            $districtsRaw[] = [
                'city_code' => $cityCode,
                'city_name' => $cityName,
                'code' => $distCode,
                'name' => $distName,
            ];
        }

        // unique district theo code
        $uniq = [];
        foreach ($districtsRaw as $d)
            $uniq[$d['code']] = $d;

        return [$cities, array_values($uniq)];
    }
}
