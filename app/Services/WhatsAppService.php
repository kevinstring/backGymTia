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

    public function sendMessage($to, $templateName, $languageCode = 'es')
    {
        $url = "{$this->apiUrl}/{$this->phoneId}/messages";

        $response = Http::withToken($this->token)
            ->post($url, [
                'messaging_product' => 'whatsapp',
                'to' => $to,
                'type' => 'template',
                'template' => [
                    'name' => $templateName,
                    'language' => [
                        'code' => $languageCode,
                    ],
                ],
            ]);

        return $response->json();
    }
}
