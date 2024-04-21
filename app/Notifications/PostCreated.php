<?php

namespace App\Notifications;

use App\Models\Admin;
use App\Models\Post;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class PostCreated extends Notification
{
    use Queueable;

    protected Admin $admin;
    protected Post $post;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Admin $admin, Post $post)
    {
        $this->admin = $admin;
        $this->post = $post;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database', 'broadcast'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return \Mirovit\NovaNotifications\Notification::make()
            ->info('A new post was created.')
            ->subtitle('There is a new post in the system!')
            ->routeDetail('posts', $this->post->id)
            ->toArray();
    }
}
