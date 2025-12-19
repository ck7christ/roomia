<?php

namespace App\Http\Controllers\Host;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Room;
use App\Models\RoomType;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        $host = $request->user();
        $now = Carbon::now();

        // Rooms + RoomTypes của host
        $roomIds = Room::where('host_id', $host->id)->pluck('id');

        $roomsCount = $roomIds->count();

        $roomTypeIds = RoomType::whereIn('room_id', $roomIds)->pluck('id');
        $roomTypesCount = $roomTypeIds->count();

        // Base query bookings thuộc host (Booking -> RoomType -> Room(host_id))
        $bookingsBase = Booking::query()
            ->whereIn('room_type_id', $roomTypeIds);

        // KPI: tháng này
        $startMonth = $now->copy()->startOfMonth();
        $endMonth = $now->copy()->endOfMonth();

        $bookingsThisMonth = (clone $bookingsBase)
            ->whereBetween('created_at', [$startMonth, $endMonth])
            ->count();

        // Revenue tháng này: sum total_price (tránh cancelled)
        $revenueThisMonth = (clone $bookingsBase)
            ->whereBetween('created_at', [$startMonth, $endMonth])
            ->whereIn('status', [
                Booking::STATUS_PENDING,
                Booking::STATUS_CONFIRMED,
                Booking::STATUS_COMPLETED,
            ])
            ->sum('total_price');

        // Today tasks
        $today = $now->toDateString();
        $checkInsToday = (clone $bookingsBase)
            ->whereDate('check_in', $today)
            ->whereIn('status', [Booking::STATUS_PENDING, Booking::STATUS_CONFIRMED])
            ->count();

        $checkOutsToday = (clone $bookingsBase)
            ->whereDate('check_out', $today)
            ->whereIn('status', [Booking::STATUS_CONFIRMED, Booking::STATUS_COMPLETED])
            ->count();

        $pendingCount = (clone $bookingsBase)
            ->where('status', Booking::STATUS_PENDING)
            ->count();

        // Status summary
        $bookingStatusCounts = (clone $bookingsBase)
            ->select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->pluck('total', 'status');

        // Upcoming check-ins / check-outs 7 ngày
        $from = $now->copy()->startOfDay();
        $to7 = $now->copy()->addDays(7)->endOfDay();

        $upcomingCheckIns = (clone $bookingsBase)
            ->whereBetween('check_in', [$from, $to7])
            ->whereIn('status', [Booking::STATUS_PENDING, Booking::STATUS_CONFIRMED])
            ->with(['guest:id,name,email', 'roomType:id,room_id,name', 'roomType.room:id,title,host_id'])
            ->orderBy('check_in')
            ->take(8)
            ->get();

        $upcomingCheckOuts = (clone $bookingsBase)
            ->whereBetween('check_out', [$from, $to7])
            ->whereIn('status', [Booking::STATUS_CONFIRMED, Booking::STATUS_COMPLETED])
            ->with(['guest:id,name,email', 'roomType:id,room_id,name', 'roomType.room:id,title,host_id'])
            ->orderBy('check_out')
            ->take(8)
            ->get();

        // Recent bookings
        $recentBookings = (clone $bookingsBase)
            ->with(['guest:id,name,email', 'roomType:id,room_id,name', 'roomType.room:id,title,host_id'])
            ->latest()
            ->take(10)
            ->get();

        // Reviews (Booking có review())
        $latestReviewedBookings = (clone $bookingsBase)
            ->whereHas('review')
            ->with(['review', 'guest:id,name,email', 'roomType:id,room_id,name', 'roomType.room:id,title,host_id'])
            ->latest()
            ->take(6)
            ->get();

        $reviewsCount = (clone $bookingsBase)->whereHas('review')->count();
        $avgRating = null;
        if ($reviewsCount > 0) {
            $avgRating = (clone $bookingsBase)
                ->whereHas('review')
                ->join('reviews', 'reviews.booking_id', '=', 'bookings.id')
                ->avg('reviews.rating');
        }

        // Top room types (30 ngày gần nhất) theo revenue
        $from30 = $now->copy()->subDays(30)->startOfDay();
        $topRoomTypeRows = (clone $bookingsBase)
            ->whereBetween('created_at', [$from30, $now])
            ->whereIn('status', [Booking::STATUS_PENDING, Booking::STATUS_CONFIRMED, Booking::STATUS_COMPLETED])
            ->select('room_type_id', DB::raw('COUNT(*) as bookings_count'), DB::raw('SUM(total_price) as revenue'))
            ->groupBy('room_type_id')
            ->orderByDesc('revenue')
            ->take(6)
            ->get();

        $topRoomTypeIds = $topRoomTypeRows->pluck('room_type_id');
        $topRoomTypesMap = RoomType::with('room:id,title,host_id')
            ->whereIn('id', $topRoomTypeIds)
            ->get()
            ->keyBy('id');

        $topRoomTypes = $topRoomTypeRows->map(function ($row) use ($topRoomTypesMap) {
            $rt = $topRoomTypesMap->get($row->room_type_id);
            return (object) [
                'room_type_id' => $row->room_type_id,
                'room_title' => optional(optional($rt)->room)->title,
                'room_type' => optional($rt)->name,
                'bookings' => (int) $row->bookings_count,
                'revenue' => (float) $row->revenue,
            ];
        });

        // Revenue 6 tháng gần nhất (group PHP để tránh phụ thuộc SQL dialect)
        $from6 = $now->copy()->subMonths(5)->startOfMonth();
        $monthlyRaw = (clone $bookingsBase)
            ->whereBetween('created_at', [$from6, $endMonth])
            ->whereIn('status', [Booking::STATUS_PENDING, Booking::STATUS_CONFIRMED, Booking::STATUS_COMPLETED])
            ->get(['created_at', 'total_price']);

        for ($i = 5; $i >= 0; $i--) {
            $m = $now->copy()->subMonths($i);
            $key = $m->format('Y-m');
            $monthlyRevenue[$key] = ['month' => $m->format('m/Y'), 'revenue' => 0, 'bookings' => 0];
        }
        foreach ($monthlyRaw as $b) {
            $key = Carbon::parse($b->created_at)->format('Y-m');
            if (isset($monthlyRevenue[$key])) {
                $monthlyRevenue[$key]['revenue'] += (float) $b->total_price;
                $monthlyRevenue[$key]['bookings'] += 1;
            }
        }
        $monthlyRevenue = collect($monthlyRevenue)->values();

        // Calendar insights (nếu có RoomCalendar)
        $calendarSummary = null;
        $calendarByRoomType = collect();

        if (class_exists(\App\Models\RoomCalendar::class)) {
            $rc = \App\Models\RoomCalendar::query();
            $table = $rc->getModel()->getTable();

            $dateCol = Schema::hasColumn($table, 'date') ? 'date' : null;
            if ($dateCol) {
                $to30 = $now->copy()->addDays(30)->endOfDay();

                $priceCol = null;
                foreach (['price_override', 'custom_price', 'price'] as $c) {
                    if (Schema::hasColumn($table, $c)) {
                        $priceCol = $c;
                        break;
                    }
                }
                $isClosedCol = Schema::hasColumn($table, 'is_closed') ? 'is_closed' : null;

                $availCol = null;
                foreach (['available_units', 'available', 'quantity'] as $c) {
                    if (Schema::hasColumn($table, $c)) {
                        $availCol = $c;
                        break;
                    }
                }

                $select = ['room_type_id', $dateCol];
                if ($isClosedCol)
                    $select[] = $isClosedCol;
                if ($priceCol)
                    $select[] = $priceCol;
                if ($availCol)
                    $select[] = $availCol;

                $cal = $rc->whereIn('room_type_id', $roomTypeIds)
                    ->whereBetween($dateCol, [$now->toDateString(), $to30->toDateString()])
                    ->get($select);

                $calendarByRoomType = $cal->groupBy('room_type_id')->map(function ($items) use ($isClosedCol, $priceCol, $availCol) {
                    $closed = $isClosedCol ? $items->where($isClosedCol, true)->count() : 0;

                    $override = 0;
                    if ($priceCol) {
                        // coi như override nếu cột có value > 0
                        $override = $items->filter(fn($x) => (float) ($x->{$priceCol} ?? 0) > 0)->count();
                    }

                    $lowAvail = 0;
                    if ($availCol) {
                        $lowAvail = $items->filter(fn($x) => (int) ($x->{$availCol} ?? 999999) <= 1)->count();
                    }

                    return [
                        'days' => $items->count(),
                        'closed_days' => $closed,
                        'price_override_days' => $override,
                        'low_availability_days' => $lowAvail,
                    ];
                });

                $calendarSummary = [
                    'room_types_tracked' => $calendarByRoomType->count(),
                    'closed_days_total' => (int) $calendarByRoomType->sum('closed_days'),
                    'override_days_total' => (int) $calendarByRoomType->sum('price_override_days'),
                    'low_availability_total' => (int) $calendarByRoomType->sum('low_availability_days'),
                ];
            }
        }

        // RoomType map để show calendar table
        $roomTypesForTable = RoomType::with('room:id,title,host_id')
            ->whereIn('id', $roomTypeIds)
            ->orderByDesc('created_at')
            ->take(12)
            ->get();

        return view('host.dashboard', compact(
            'roomsCount',
            'roomTypesCount',
            'bookingsThisMonth',
            'revenueThisMonth',
            'checkInsToday',
            'checkOutsToday',
            'pendingCount',
            'bookingStatusCounts',
            'upcomingCheckIns',
            'upcomingCheckOuts',
            'recentBookings',
            'latestReviewedBookings',
            'reviewsCount',
            'avgRating',
            'topRoomTypes',
            'monthlyRevenue',
            'calendarSummary',
            'calendarByRoomType',
            'roomTypesForTable'
        ));
    }
}
