<?php

namespace App\Http\Controllers;

use App\Services\WhatsAppService;
use Illuminate\Http\Request;

class WhatsAppController extends Controller
{
    private $whatsappService;

    public function __construct(WhatsAppService $whatsappService)
    {
        $this->whatsappService = $whatsappService;
    }

    public function sendTemplateMessage(Request $request)
    {
        $to = $request->input('to');
        $templateName = $request->input('template_name');
        $languageCode = $request->input('language_code', 'es');

        $response = $this->whatsappService->sendMessage($to, $templateName, $languageCode);

        return response()->json($response);
    }
}
