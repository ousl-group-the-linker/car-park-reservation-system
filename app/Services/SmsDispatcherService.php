<?php

namespace App\Services;

use Exception;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SmsDispatcherService
{
    private $url = "https://app.notify.lk/api/v1/send";
    public function send(string $receiverPhoneNumber, string $message)
    {

        if (strlen($receiverPhoneNumber) == 10 && str_split($receiverPhoneNumber, 1)[0] == 0) {
            $receiverPhoneNumber = "94" . substr($receiverPhoneNumber, 1);
        }
        try {
            $response = Http::post($this->url, [
                "user_id" => config("notifylk.user_id"),
                "api_key" => config("notifylk.api_key"),
                "sender_id" => config("notifylk.sender_id"),
                "to" => $receiverPhoneNumber,
                "message" => $message,
            ]);

            $body = json_decode($response->body());

            if ($body->status == "error") {
                Log::error("Error sending SMS ({$response}).");
            }
            return true;
        } catch (Exception $e) {

            Log::error("Error sending SMS ({$e->getMessage()}).", $e->getTrace());

            return false;
        }
    }
}
