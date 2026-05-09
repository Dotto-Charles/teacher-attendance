<?php

namespace App\Services;

use App\Models\Attendance;
use App\Models\School;
use App\Models\User;
use App\Models\Ward;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class AttendanceService
{
    // ── CACHE TTL ────────────────────────────────────────────────
    const TTL_TODAY      = 300;  // 5 minutes for today
    const TTL_HISTORICAL = 3600; // 1 hour for historical
    const TTL_SUMMARY    = 600;  // 10 minutes for summaries
    const TTL_TEACHERS   = 900;  // 15 minutes for teacher lists

 public function dailyTrend(string $scope, int $id, int $days = 14): Collection
{
    $ttl = self::TTL_SUMMARY;

    $result = Cache::remember("att:trend:{$scope}:{$id}:{$days}", $ttl, function () use ($scope, $id, $days) {

        $from = Carbon::today()->subDays($days - 1)->toDateString();
        $to   = Carbon::today()->toDateString();

        $query = Attendance::selectRaw('DATE(created_at) as date, COUNT(DISTINCT user_id) as count')
            ->whereBetween(DB::raw('DATE(created_at)'), [$from, $to])
            ->groupBy(DB::raw('DATE(created_at)'));

        if ($scope === 'school') {
            $query->where('school_id', $id);

            $total = User::where('school_id', $id)
                ->whereIn('role', ['teacher','head_teacher'])
                ->where('status', 'approved')
                ->count();

        } elseif ($scope === 'ward') {
            $schoolIds = School::where('ward_id', $id)->pluck('id');

            $query->whereIn('school_id', $schoolIds);

            $total = User::whereIn('school_id', $schoolIds)
                ->whereIn('role', ['teacher','head_teacher'])
                ->where('status', 'approved')
                ->count();

        } else { // council
            $wardIds = Ward::where('council_id', $id)->pluck('id');
            $schoolIds = School::whereIn('ward_id', $wardIds)->pluck('id');

            $query->whereIn('school_id', $schoolIds);

            $total = User::whereIn('school_id', $schoolIds)
                ->whereIn('role', ['teacher','head_teacher'])
                ->where('status', 'approved')
                ->count();
        }

        $map = $query->pluck('count', 'date');

        $data = []; // ✅ BADILISHA HAPA (array badala ya collection)

        for ($i = $days - 1; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i)->toDateString();
            $cnt  = $map[$date] ?? 0;

            $rate = $total > 0 ? round(($cnt / $total) * 100, 1) : 0;

            $data[] = [
                'date'  => Carbon::parse($date)->format('d M'),
                'count' => $cnt,
                'rate'  => $rate,
            ];
        }

        return $data; // ✅ array
    });

    return collect($result); // ✅ geuza kuwa collection hapa nje
}

    // ────────────────────────────────────────────────────────────
    // SCHOOL DAILY SUMMARY
    // ────────────────────────────────────────────────────────────
    public function schoolDailySummary(int $schoolId, string $date): array
    {
        $ttl = Carbon::parse($date)->isToday() ? self::TTL_TODAY : self::TTL_HISTORICAL;

        return Cache::remember("att:school:{$schoolId}:{$date}", $ttl, function () use ($schoolId, $date) {

            $teacherIds = User::where('school_id', $schoolId)
                ->whereIn('role', ['teacher','head_teacher'])
                ->where('status', 'approved')
                ->pluck('id');

            $presentIds = Attendance::where('school_id', $schoolId)
                ->whereDate('created_at', $date)
                ->whereIn('user_id', $teacherIds)
                ->distinct('user_id')
                ->pluck('user_id');

            $total   = $teacherIds->count();
            $present = $presentIds->count();
            $absent  = max(0, $total - $present);
            $rate    = $total > 0 ? round(($present / $total) * 100, 1) : 0;

            return compact('total', 'present', 'absent', 'rate', 'presentIds');
        });
    }

    // ────────────────────────────────────────────────────────────
    // WARD DAILY SUMMARY
    // ────────────────────────────────────────────────────────────
    public function wardDailySummary(int $wardId, string $date): array
    {
        $ttl = Carbon::parse($date)->isToday() ? self::TTL_TODAY : self::TTL_HISTORICAL;

        return Cache::remember("att:ward:{$wardId}:{$date}", $ttl, function () use ($wardId, $date) {

            $schoolIds = School::where('ward_id', $wardId)->pluck('id');

            $total = User::whereIn('school_id', $schoolIds)
                ->whereIn('role', ['teacher','head_teacher'])
                ->where('status', 'approved')
                ->count();

            $present = Attendance::whereIn('school_id', $schoolIds)
                ->whereDate('created_at', $date)
                ->distinct('user_id')
                ->count('user_id');

            $absent = max(0, $total - $present);
            $rate   = $total > 0 ? round(($present / $total) * 100, 1) : 0;

            return compact('total', 'present', 'absent', 'rate');
        });
    }

    // ────────────────────────────────────────────────────────────
    // SCHOOL TEACHER STATS (FIXED SQL ERROR HERE)
    // ────────────────────────────────────────────────────────────
    public function schoolTeacherStats(int $schoolId, string $from, string $to): Collection
{
    $ttl = Carbon::parse($to)->isToday()
        ? self::TTL_TODAY
        : self::TTL_HISTORICAL;

    $workDays = max($this->countWorkDays($from, $to), 1);

    $key = "att:school_teachers:{$schoolId}:{$from}:{$to}";

    $fetch = function () use ($schoolId, $from, $to, $workDays) {
        $rows = DB::select("
            SELECT
                u.id,
                u.first_name,
                u.last_name,
                u.check_number,
                u.sex,
                u.status,
                u.role,
                u.created_at AS joined,

                COUNT(DISTINCT DATE(a.created_at)) AS days_present,

                ? AS working_days,

                ROUND(
                    (COUNT(DISTINCT DATE(a.created_at)) / ?) * 100,
                    1
                ) AS rate

            FROM users u
            LEFT JOIN attendances a
                ON a.user_id = u.id
                AND DATE(a.created_at) BETWEEN ? AND ?

            WHERE u.school_id = ?
              AND u.role IN ('teacher','head_teacher')

            GROUP BY
                u.id, u.first_name, u.last_name,
                u.check_number, u.sex, u.status,
                u.role, u.created_at

            ORDER BY rate DESC, u.first_name
        ", [
            $workDays,
            $workDays,
            $from,
            $to,
            $schoolId
        ]);

        return array_map(fn($row) => (array) $row, $rows);
    };

    try {
        $result = Cache::remember($key, $ttl, $fetch);
    } catch (\Throwable $e) {
        Cache::forget($key);
        $result = Cache::remember($key, $ttl, $fetch);
    }

    return collect($result)->map(fn($row) => (object) $row);
}

    // ────────────────────────────────────────────────────────────
    // TEACHER PERIOD STATS
    // ────────────────────────────────────────────────────────────
    public function teacherPeriodStats(int $userId, string $from, string $to): array
    {
        $fromDate = Carbon::parse($from)->toDateString();
        $toDate   = Carbon::parse($to)->toDateString();

        $ttl = Carbon::parse($to)->isToday() ? self::TTL_TODAY : self::TTL_HISTORICAL;

        return Cache::remember("att:teacher:{$userId}:{$fromDate}:{$toDate}", $ttl, function () use ($userId, $fromDate, $toDate) {

            $days = Attendance::where('user_id', $userId)
                ->whereBetween(DB::raw('DATE(created_at)'), [$fromDate, $toDate])
                ->selectRaw('DATE(created_at) as date')
                ->distinct()
                ->pluck('date');

            $workDays = max($this->countWorkDays($fromDate, $toDate), 1);

            $present = $days->count();
            $rate    = round(($present / $workDays) * 100, 1);

            return [
                'days_present' => $present,
                'working_days' => $workDays,
                'rate'         => $rate,
                'dates'        => $days->toArray(),
            ];
        });
    }

    // ────────────────────────────────────────────────────────────
    // WORKING DAYS CALCULATOR
    // ────────────────────────────────────────────────────────────
    public function countWorkDays(string $from, string $to): int
    {
        return Cache::remember("workdays:{$from}:{$to}", 86400, function () use ($from, $to) {

            $count = 0;
            $d = Carbon::parse($from);
            $end = Carbon::parse($to);

            while ($d->lte($end)) {
                if ($d->isWeekday()) {
                    $count++;
                }
                $d->addDay();
            }

            return $count;
        });
    }
}