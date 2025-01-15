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

    public function sendMessage(Request $request)
    {
        $to = $request->input('to');
        $templateName = $request->input('prueba');
        $languageCode = $request->input('language_code', 'es');
        $parameters = $request->input('parameters');  // Recibe los valores para los parámetros
    
        // Llamar al servicio para enviar el mensaje
        $response = $this->whatsappService->sendMessage($to, $templateName, $languageCode, $parameters);
    
        return response()->json($response);
    }
}
