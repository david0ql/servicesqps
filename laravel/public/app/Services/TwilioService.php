<?php

namespace App\Services;

use Twilio\Rest\Client;
use Twilio\Http\CurlClient;
use Illuminate\Support\Facades\Log;

class TwilioService
{
    protected $client;

    public function __construct()
    {
        $sid = env('TWILIO_SID');
        $token = env('TWILIO_TOKEN');

        $httpClient = new CurlClient([
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => false,
        ]);

        $this->client = new Client($sid, $token, null, null, $httpClient);
    }

    public function sendSms($to, $message)
    {
        try {
            $from = env('TWILIO_SENDER_NUMBER');
            if (!$from || !$to) {
                throw new \Exception('Sender or receiver number is not set.');
            }

            $this->client->messages->create($to, [
                'from' => $from,
                'body' => $message
            ]);
        } catch (\Exception $e) {
            Log::error('Error sending SMS: ' . $e->getMessage());
        }
    }
}
