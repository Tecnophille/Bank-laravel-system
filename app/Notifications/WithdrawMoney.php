<?php

namespace App\Notifications;

use App\Channels\SmsMessage;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class WithdrawMoney extends Notification {
    use Queueable;

    private $transaction;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($transaction) {
        $this->transaction = $transaction;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable) {
        return [\App\Channels\TwilioSms::class, 'database'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable) {
        return (new MailMessage)
            ->line('The introduction to the notification.')
            ->action('Notification Action', url('/'))
            ->line('Thank you for using our application!');
    }

    /**
     * Get the sms representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toTwilioSms($notifiable) {
        $amount   = decimalPlace($this->transaction->amount, currency($this->transaction->currency->name));
        $dateTime = $this->transaction->created_at;
        return (new SmsMessage())
            ->setContent("Dear Sir, Your account has been debited by $amount on $dateTime")
            ->setRecipient($notifiable->country_code . $notifiable->phone);
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable) {
        $amount   = decimalPlace($this->transaction->amount, currency($this->transaction->currency->name));
        $dateTime = $this->transaction->created_at;
        return [
            'message' => "Dear Sir, Your account has been debited by $amount on $dateTime",
        ];
    }
}
