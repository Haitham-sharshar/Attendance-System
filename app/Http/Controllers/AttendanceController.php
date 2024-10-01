<?php

namespace App\Http\Controllers;

use App\Http\Requests\AttendanceRequest;
use App\Services\AttendanceService;
use App\Traits\ApiResponse;
use App\Http\Resources\AttendanceResource; // Import AttendanceResource
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    use ApiResponse;

    protected $attendanceService;

    public function __construct(AttendanceService $attendanceService)
    {
        $this->attendanceService = $attendanceService;
    }

    /**
     * Handle check-in or check-out.
     *
     * @param AttendanceRequest $request
     * @return JsonResponse
     */
    public function recordAttendance(AttendanceRequest $request): JsonResponse
    {
        $data = $request->validated();
        $attendance = $this->attendanceService->recordAttendance($data);

        // attendance data
        return $this->successResponse([
            'attendance' => new AttendanceResource($attendance),
        ], ucfirst($data['action_type']) . ' recorded successfully.', 200);
    }

    /**
     * Get total hours worked by the user.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function getTotalHours(Request $request, $userId): JsonResponse
    {
       // get the date from and to
        $from = $request->input('from', now()->startOfMonth()->toDateString()); // Use current month start if 'from' is not provided
        $to = $request->input('to', now()->endOfMonth()->toDateString()); // Use current month end if 'to' is not provided

        // Calculate total hours for the specified user within the period
        $totalHours = $this->attendanceService->calculateTotalHours($userId, $from, $to);

        // Round the total hours to 2 decimal places
        $roundedTotalHours = round($totalHours);

        return $this->successResponse(['total_hours' => $roundedTotalHours], 'Total hours retrieved successfully.');
    }
}
