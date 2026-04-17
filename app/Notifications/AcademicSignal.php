<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class AcademicSignal extends Notification
{
    use Queueable;

    protected $event;

    /**
     * Create a new notification instance.
     *
     * @param \App\Models\AcademicEvent $event
     */
    public function __construct($event)
    {
        $this->event = $event;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            'type' => 'academic',
            'event_id' => $this->event->id,
            'title' => 'New Academic Pulse: ' . $this->event->title,
            'message' => 'A new ' . $this->event->type . ' has been manifested for your batch.',
            'due_date' => $this->event->due_date->format('d M, Y'),
            'action_url' => route('dashboard'),
            'icon' => $this->getIcon(),
        ];
    }

    protected function getIcon()
    {
        $icons = [
            'exam' => '📁',
            'mst' => '📑',
            'project' => '🏗️',
            'assignment' => '📝',
            'quiz' => '⏱️',
            'lab' => '🧪',
            'other' => '🗓️'
        ];
        return $icons[$this->event->type] ?? '⚡';
    }
}
