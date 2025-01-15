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
    
        // Obtener los parámetros desde el request
        $parameters = [
            [
                'type' => 'text',
                'parameter_name' => 'name', // Nombre del parámetro en la plantilla
                'text' =>'Texto de prueba etiquetar a persona' // Valor del parámetro
            ],
            [
                'type' => 'text',
                'parameter_name' => 'dias', // Nombre del parámetro en la plantilla
                'text' =>'50' // Valor del parámetro
            ]
        ];
    
        // Validar que los parámetros estén bien formateados
        if (!is_array($parameters) || empty($parameters)) {
            return response()->json(['error' => 'El parámetro "parameters" debe ser un array y no estar vacío'], 400);
        }
    
        // Llamar al servicio de WhatsApp para enviar el mensaje
        $response = $this->whatsappService->sendMessage('+50251270746', $templateName, $languageCode, $parameters);
    
        // Retornar la respuesta del servicio
        return response()->json($response);
    }
    
    
}
