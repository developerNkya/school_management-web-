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

    // public function handle()
    // {
    //     $username = Constants::NEXT_USERNAME;
    //     $password = Constants::NEXT_PASSWORD;

    //     $postData = [
    //         'source_addr' => 'INFO',
    //         'encoding' => 0,
    //         'message' => $this->message,
    //         'recipients' => [
    //             [
    //                 'recipient_id' => '1',
    //                 'dest_addr' => $this->phone
    //             ]
    //         ]
    //     ];

    //     $url = 'https://apisms.beem.africa/v1/send';
    //     $client = new Client();

    //     try {
    //         $response = $client->post($url, [
    //             'headers' => [
    //                 'Authorization' => 'Basic ' . base64_encode("$api_key:$secret_key"),
    //                 'Content-Type' => 'application/json',
    //             ],
    //             'json' => $postData,
    //         ]);
    //         \Log::info("SMS sent successfully to {$this->phone}: " . $response->getBody());

    //     } catch (\Exception $e) {
    //         \Log::error("Failed to send SMS to {$this->phone}: " . $e->getMessage());
    //     }
    // }

    public function handle()
{
    $username = Constants::NEXT_USERNAME;
    $password = Constants::NEXT_PASSWORD;

    $postData = [
        'from' => 'SCHOOL',
        'to' => $this->phone,
        'text' => $this->message
    ];

    $url = 'https://messaging-service.co.tz/api/sms/v1/text/single';


    $curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => json_encode($postData),
        CURLOPT_HTTPHEADER => array(
            'Content-Type: application/json',
            'Accept: application/json',
            'Authorization: Basic ' . base64_encode("$username:$password")
        ),
    ));

    $response = curl_exec($curl);
    if (curl_errno($curl)) {
        \Log::error("Failed to send SMS to {$this->phone}: " . curl_error($curl));
    } else {
        \Log::info("SMS sent successfully to {$this->phone}: " . $response);
    }
    curl_close($curl);
}

}

