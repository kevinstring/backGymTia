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
        // Obtener los datos del request
        $to = $request->input('to'); // El número de teléfono
        $templateName = $request->input('name'); // El nombre de la plantilla
        $languageCode = $request->input('code', 'es'); // El código de idioma (por defecto "es")
        $parameters = [
           ['type' => 'body',
            'parameter_name'=>'name',
            'text'=>'texto de prueba etiquetar a persona'] ,
            ['type' => 'body',
            'parameter_name'=>'dias',
            'text'=>'50'] ,

        ];
    
        // Asegurarse de que los parámetros sean un array con el formato correcto
        if (!is_array($parameters)) {
            return response()->json(['error' => 'El parámetro "parameters" debe ser un array'], 400);
        }
    
        // Llamar al servicio de WhatsApp para enviar el mensaje
        $response = $this->whatsappService->sendMessage( "+50235518257", $templateName, $languageCode, $parameters);
    
        // Retornar la respuesta del API de WhatsApp
        return response()->json($response);
    }
    
}
