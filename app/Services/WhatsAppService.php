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

    public function sendMessage($to, $templateName, $languageCode = 'es', $parameters )
    {
        $url = "{$this->apiUrl}/{$this->phoneId}/messages";

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->token, // Asegúrate de incluir tu token aquí
            'Content-Type' => 'application/json',
        ])->post($url, [
            'messaging_product' => 'whatsapp',
            'to' => $to,
            'type' => 'template',
            'template' => [
                'name' => 'prueba',
                'language' => ['code' => 'es'],
                'components' => [
                    [
                        'type' => 'body',
                        'parameters' => $parameters
                    ]
                ]
            ]
        ]);


        if ($response->failed()) {
            // Manejo del error
            return [
                'success' => false,
                'message' => $response->json(),
            ];
        }
    
        return [
            'success' => true,
            'message' => $response->json(),
        ];    }
}
