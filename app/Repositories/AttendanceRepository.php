<?php
namespace App\Repositories;

use App\Models\Attendance;
use Illuminate\Database\Eloquent\Builder;

class AttendanceRepository
{
    /**
     * Store a new attendance record.
     *
     * @param array $data
     * @return Attendance
     */
    public function storeAttendance(array $data)
    {
        return Attendance::create($data);
    }

    /**
     * Prepare attendance records for a user with optional filters.
     *
     * @param int $userId
     * @param string|null $actionType
     * @param array|null $dateRange
     * @return Builder
     */
    protected function prepareAttendanceRecords($userId, $actionType = null, $dateRange = null): Builder
    {
        $query = Attendance::where('user_id', $userId);

        if ($actionType) {
            $query->where('action_type', $actionType);
        }

        if ($dateRange && isset($dateRange['from'], $dateRange['to'])) {
            $query->whereBetween('action_time', [$dateRange['from'], $dateRange['to']]);
        }

        return $query;
    }

    /**
     * Get all attendance records for a user by action type (check-in or check-out).
     *
     * @param int $userId
     * @param string $actionType
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAttendanceByType($userId, $actionType)
    {
        return $this->prepareAttendanceRecords($userId, $actionType)->get();
    }

    /**
     * Get total attendance hours for a user in a date range.
     *
     * @param int $userId
     * @param string $from
     * @param string $to
     * @return float
     */
    public function getTotalHours($userId, $from, $to)
    {
        // Get all check-ins and check-outs within Period
        $checkIns = $this->prepareAttendanceRecords($userId, 'check_in', ['from' => $from, 'to' => $to])
            ->pluck('action_time');

        $checkOuts = $this->prepareAttendanceRecords($userId, 'check_out', ['from' => $from, 'to' => $to])
            ->pluck('action_time');

        $totalMinutes = 0;

        // Calculate total hours in minutes
        for ($i = 0; $i < count($checkIns); $i++) {
            if (isset($checkOuts[$i])) {
                if ($checkIns[$i]->lessThan($checkOuts[$i])) {
                    $totalMinutes += $checkIns[$i]->diffInMinutes($checkOuts[$i]);
                }
            }
        }

        // Convert total minutes to hours
        $totalHours = $totalMinutes / 60;

        return $totalHours;
    }
}
