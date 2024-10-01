<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class MonthlyAttendanceSummary extends Notification
{
    use Queueable;

    protected $totalHours;
    protected $month;

    public function __construct($totalHours, $month)
    {
        $this->totalHours = $totalHours;
        $this->month = $month;
    }

    /**
     * Specify how the notification should be delivered (via email).
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Build the email notification message.
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Your Monthly Work Hours Summary for ' . $this->month)
            ->greeting('Hello ' . $notifiable->name . ',')
            ->line('You worked a total of ' . $this->totalHours . ' hours in ' . $this->month . '.')
            ->line('Thank you for your effort and dedication.');
    }

}
