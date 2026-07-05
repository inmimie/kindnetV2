<?php

namespace App\Notifications;

use App\Models\Application;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class ApplicationApprovedNotification extends Notification
{
    use Queueable;

    protected $application;

    /**
     * Create a new notification instance.
     */
    public function __construct(Application $application)
    {
        $this->application = $application;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'application_id' => $this->application->id,
            'charity_type_name' => $this->application->charityType->name,
            'message' => "Your charity application #{$this->application->id} for {$this->application->charityType->name} has been approved.",
            'approved_at' => $this->application->approved_at ? $this->application->approved_at->toIso8601String() : now()->toIso8601String(),
        ];
    }
}
