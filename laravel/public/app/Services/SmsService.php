<?php

namespace App\Services;

use Vonage\SMS\Message\SMS;
use Vonage\Client;

class SmsService
{
    protected Client $client;

    public function __construct()
    {
        $this->client = new Client(new \Vonage\Client\Credentials\Basic('dbee6431', '2YnHOefkGSEdPYwU'));
    }

    public function sendSms($to, $message)
    {
        $response = $this->client->sms()->send(
            new SMS($to, 'QPS', $message)
        );

        $message = $response->current();

        return $message->getStatus() == 0;
    }
}
