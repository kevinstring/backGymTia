<?php

namespace App\Console\Commands;


use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Services\WhatsAppService;
use Illuminate\Http\Request;



class mandarMensajes extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:mandar-mensajes';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */

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
     
   
     }
    // public function handle()
    // {
    //     //

    //     $obtenerDias = DB::TABLE("USUARIO_INSCRITO")->
    //     leftjoin("USUARIO as user")
    //     ->get();


    //     foreach($obtenerDias as $dias){
    //         if($dias->DIAS_PENDIENTES==2){


    //             $response = $this->whatsappService->sendMessage('+50235518257', $templateName, 'es', $parameters);


    //         }
    //     }
    //   // Llamar al servicio de WhatsApp para enviar el mensaje
     
    //   // Retornar la respuesta del servicio
    //   return response()->json($response);


    // }
}
