<?php

use App\Models\User;
use App\Services\AttendanceService;
use Illuminate\Support\Facades\Schedule;

Schedule::call(function () {
    $users = User::all();
    foreach ($users as $user) {
        //  send the monthly summary
        app(AttendanceService::class)->sendMonthlySummary($user);
    }
})->monthlyOn(1, '00:00'); // Run the task on the 1st day of every month at midnight
