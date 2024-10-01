<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Attendance;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Carbon\Carbon;

class AttendanceTest extends TestCase
{
    use RefreshDatabase; // This will reset the database after each test

    /**
     * Test that attendance can be created with check-in and check-out and total hours calculated.
     *
     * @return void
     */
    public function test_attendance_can_calculate_exact_total_hours()
    {
        // Create a user using factory
        $user = User::factory()->create();

        // Set check-in and check-out times manually to ensure exact hours difference
        $checkInTime = Carbon::parse('2024-01-01 08:00:00');
        $checkOutTime = Carbon::parse('2024-01-01 18:00:00');

        // Create a check-in record using factory
        $checkIn = Attendance::factory()->create([
            'user_id' => $user->id,
            'action_type' => 'check_in',
            'action_time' => $checkInTime,
        ]);

        // Create a check-out record using factory
        $checkOut = Attendance::factory()->create([
            'user_id' => $user->id,
            'action_type' => 'check_out',
            'action_time' => $checkOutTime,
        ]);

        // Generate JWT token for the user
        $token = auth('api')->login($user);

        // Get total hours worked by sending a request
        $response = $this->withHeaders([
            'Authorization' => "Bearer $token",
        ])->getJson(route('attendances.hours', [
            'userId' => $user->id,
            'from' => $checkInTime->toDateTimeString(),
            'to' => $checkOutTime->toDateTimeString(),
        ]));

        // Assert that the response status is 200 (success)
        $response->assertStatus(200);

        // Extract total hours from the response JSON
        $responseData = $response->json('data');

        // We expect exactly 10 hours between 8:00 AM and 6:00 PM
        $expectedHours = 10;

        // Assert that the total_hours is exactly 10
        $this->assertEquals($expectedHours, $responseData['total_hours']);
    }
}
