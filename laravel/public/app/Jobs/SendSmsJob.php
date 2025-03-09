<?php

namespace App\Jobs;

use App\Services\SmsService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class SendSmsJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    protected $phoneNumber;
    protected $from;
    protected $text;

    public function __construct($phoneNumber, $from, $text)
    {
        $this->phoneNumber = $phoneNumber;
        $this->from = $from;
        $this->text = $text;
    }

    public function handle()
    {
        $smsService = new SmsService();
        try {
            $smsService->sendSms($this->phoneNumber, $this->from, $this->text);
        } catch (\Exception $e) {
            Log::error('Error sending SMS: ' . $e->getMessage());
        }
    }
}
