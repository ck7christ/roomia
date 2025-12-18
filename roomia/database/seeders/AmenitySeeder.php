<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Amenity;

class AmenitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $items = [
            ['name' => 'Wifi', 'code' => 'wifi', 'group' => 'internet', 'icon_class' => 'fa-solid fa-wifi'],
            ['name' => 'TV', 'code' => 'tv', 'group' => 'entertainment', 'icon_class' => 'fa-solid fa-tv'],
            ['name' => 'Điều hòa', 'code' => 'air_conditioner', 'group' => 'comfort', 'icon_class' => 'fa-solid fa-snowflake'],
            ['name' => 'Máy giặt', 'code' => 'washing_machine', 'group' => 'appliances', 'icon_class' => 'fa-solid fa-soap'],
            ['name' => 'Máy sấy', 'code' => 'dryer', 'group' => 'appliances', 'icon_class' => 'fa-solid fa-wind'],
            ['name' => 'Bãi đậu xe miễn phí', 'code' => 'free_parking', 'group' => 'parking', 'icon_class' => 'fa-solid fa-car'],
            ['name' => 'Hồ bơi', 'code' => 'swimming_pool', 'group' => 'facilities', 'icon_class' => 'fa-solid fa-water-ladder'],
        ];

        foreach ($items as $index => $item) {
            Amenity::updateOrCreate(
                ['code' => $item['code']],
                [
                    'name' => $item['name'],
                    'group' => $item['group'],
                    'icon_class' => $item['icon_class'],
                    'is_active' => true,
                    'sort_order' => $index,
                ]
            );
        }
    }
}
