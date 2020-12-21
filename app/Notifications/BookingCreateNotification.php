<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Auth;

class BookingCreateNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($params)
    {
        $this->params = $params;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->subject($this->params['subject'])
                    ->greeting('Hallo ' . Auth::user()->email . ' !')
                    ->line($this->params['title'])
                    ->line('=========================================================')
                    ->line('Room : ' . $this->params['room_name'])
                    ->line('Booking Date : ' . $this->params['booking_time'])
                    ->line('Total Person : ' . $this->params['total_person'])
                    ->line('Noted : ' .  $this->params['noted'])
                    ->line('=========================================================')
                    // ->action('Notification Action', url('/'))
                    ->line('Thank you for using our services!');
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
            //
        ];
    }
}
