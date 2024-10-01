# Attendance System Backend - README

Overview
This project is a simple Attendance System backend built with Laravel. The system allows users to track their working hours by checking in and checking out multiple times per day. Users can also calculate their total hours worked within a specific date range and receive a monthly summary notification of their hours worked.

The project implements the following key functionalities:

User Authentication using JWT (Login, Register).
Check-in and Check-out for attendance with timestamps.
Total hours calculation between check-in and check-out times.
Monthly summary notification sent to users on the 1st of every month.
Unit Testing to ensure that key functionalities work as expected.
Features
User Authentication

Users can register and log in using their email and password.
Token-based authentication using JWT (JSON Web Token).
Attendance Tracking

we can record their check-in and check-out times for user.
Multiple check-ins and check-outs per day are supported.
Total Hours Calculation

Users can query the total hours worked over a specific date range.
Monthly Notifications

On the 1st of each month, users receive a notification of their total hours worked in the previous month via Laravel Notifications.
API-based Architecture

The system is designed as a RESTful API.
Token-based access control using JWT.
Unit Testing
