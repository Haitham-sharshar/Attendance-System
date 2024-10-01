<?php

namespace App\Console;

use App\Notifications\MonthlyAttendanceSummary;
use App\Services\AttendanceService;
use Carbon\Carbon;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     *
     * @param Schedule $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // Define a scheduled task to send monthly attendance summaries on the 1st of every month
        $schedule->call(function () {
            // Get all users
            $users = \App\Models\User::all();
            $lastMonth = Carbon::now()->subMonth(); // Get the previous month

            foreach ($users as $user) {
                // Calculate total hours worked in the last month using AttendanceService
                $attendanceService = app(AttendanceService::class);
                $totalHours = $attendanceService->calculateTotalHours(
                    $user->id,
                    $lastMonth->startOfMonth(),
                    $lastMonth->endOfMonth()
                );

                // Send the monthly summary notification
                $user->notify(new MonthlyAttendanceSummary($totalHours, $lastMonth->format('F')));
            }
        })->monthlyOn(1, '00:00'); // Run the task on the 1st of every month
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');
        require base_path('routes/console.php');
    }
}
