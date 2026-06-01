<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;

class SmsService
{
    /**
     * Simulate sending an SMS.
     *
     * @param string $phoneNumber
     * @param string $message
     * @return bool
     */
    public function sendSms(string $phoneNumber, string $message): bool
    {
        // In a real application, you would connect to Twilio or similar here.
        Log::info("MOCK SMS to {$phoneNumber}: {$message}");
        
        return true;
    }
}
