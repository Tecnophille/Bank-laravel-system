<?php

namespace App\Channels;

use Illuminate\Notifications\Notification;
use Twilio\Rest\Client;

class TwilioSms {
    /**
     * @param $notifiable
     * @param Notification $notification
     * @throws \Twilio\Exceptions\TwilioException
     */
    public function send($notifiable, Notification $notification) {
        $message = $notification->toTwilioSms($notifiable);
        if (get_option('enable_sms') == 0) {
            return;
        }

        $account_sid   = get_option('twilio_account_sid');
        $auth_token    = get_option('twilio_auth_token');
        $twilio_number = get_option('twilio_mobile_number');
        $client        = new Client($account_sid, $auth_token);

        try {
            $client->messages->create('+' . $message->getRecipient(),
                ['from' => $twilio_number, 'body' => $message->getContent()]);
        } catch (\Exception $e) {}

    }
}