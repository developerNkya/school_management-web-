<?php

namespace App\Jobs;

use GuzzleHttp\Client;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Constants;

class SendSms implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $phone;
    protected $message;

    public function __construct($phone, $message)
    {
        $this->phone = $phone;
        $this->message = $message;
    }

    public function handle()
    {
        $api_key = Constants::BEEM_API_KEY;
        $secret_key = Constants::BEEM_SECRET_KEY;

        $postData = [
            'source_addr' => 'INFO',
            'encoding' => 0,
            'message' => $this->message,
            'recipients' => [
                [
                    'recipient_id' => '1',
                    'dest_addr' => $this->phone
                ]
            ]
        ];

        $url = 'https://apisms.beem.africa/v1/send';
        $client = new Client();

        try {
            $response = $client->post($url, [
                'headers' => [
                    'Authorization' => 'Basic ' . base64_encode("$api_key:$secret_key"),
                    'Content-Type' => 'application/json',
                ],
                'json' => $postData,
            ]);
            \Log::info("SMS sent successfully to {$this->phone}: " . $response->getBody());

        } catch (\Exception $e) {
            \Log::error("Failed to send SMS to {$this->phone}: " . $e->getMessage());
        }
    }
}

