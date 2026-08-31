<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class ConferenceNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public string $title,
        public string $message,
        public string $actionText = '',
        public string $actionUrl = '',
        public string $type = 'info',
    ) {}

    public function via(object $notifiable): array
    {
        return [
            'database',
        ];
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'title' => $this->title,
            'message' => $this->message,
            'action_text' => $this->actionText,
            'action_url' => $this->actionUrl,
            'type' => $this->type,
        ];
    }
}
