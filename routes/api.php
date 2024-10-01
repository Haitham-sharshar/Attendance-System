<?php

use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;


// Auth login
Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);

Route::middleware('auth:api')->group(function () {

    // Route for recording attendance (check-in or check-out)
    Route::post('attendances', [AttendanceController::class, 'recordAttendance'])->name('attendances.record');

    // Route for retrieving total hours worked in a date range
    Route::get('attendance/hours/{userId}', [AttendanceController::class, 'getTotalHours'])->name('attendances.hours');

});
