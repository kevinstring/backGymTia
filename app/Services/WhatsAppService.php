<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class WhatsAppService
{
    private $token;
    private $phoneId;
    private $apiUrl;

    public function __construct()
    {
        $this->token = env('WHATSAPP_TOKEN');
        $this->phoneId = env('WHATSAPP_PHONE_ID');
        $this->apiUrl = env('WHATSAPP_API_URL');
    }

    public function sendMessage($to, $templateName, $languageCode = 'es', $parameters = [])
    {
        $url = "https://graph.facebook.com/v17.0/$this->phoneId}/messages";
       

        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
        ])->post($url, [
            'messaging_product' => 'whatsapp',
            'to' => $to,
            'type' => 'template',
            'template' => [
                'name' => $templateName,
                'language' => ['code' => $languageCode],
                'components' => [
                    [
                        'type' => 'body',
                        'parameters' => $parameters
                    ]
                ]
            ]
        ]);

        return $response->json();
    }
}
