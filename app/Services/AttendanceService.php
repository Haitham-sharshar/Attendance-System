<?php

namespace App\Services;

use App\Repositories\AttendanceRepository;
use App\Notifications\MonthlyAttendanceSummary;
use Carbon\Carbon;

class AttendanceService
{
    protected $attendanceRepository;

    public function __construct(AttendanceRepository $attendanceRepository)
    {
        $this->attendanceRepository = $attendanceRepository;
    }

    /**
     * Handle storing attendance (check-in or check-out).
     *
     * @param array $data
     * @return \App\Models\Attendance
     */
    public function recordAttendance(array $data)
    {
        // Set the current time as action_time
        $data['action_time'] = now();
        return $this->attendanceRepository->storeAttendance($data);
    }

    /**
     * Calculate total hours worked by a user between two dates.
     *
     * @param int $userId
     * @param string $from
     * @param string $to
     * @return float
     */
    public function calculateTotalHours($userId, $from, $to)
    {
        return $this->attendanceRepository->getTotalHours($userId, $from, $to);
    }

    /**
     * Send monthly summary of work hours to the user.
     *
     * @param \App\Models\User $user
     * @return void
     */
    public function sendMonthlySummary($user)
    {
        // Get the previous month
        $lastMonth = Carbon::now()->subMonth();
        $from = $lastMonth->startOfMonth()->toDateTimeString();
        $to = $lastMonth->endOfMonth()->toDateTimeString();

        // Calculate total hours worked in the last month
        $totalHours = $this->attendanceRepository->getTotalHours($user->id, $from, $to);

        // Send the notification to the user
        $user->notify(new MonthlyAttendanceSummary($totalHours, $lastMonth->format('F')));
    }
}
