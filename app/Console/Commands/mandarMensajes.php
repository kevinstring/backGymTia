<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;


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
    public function handle()
    {
        //

        $obtenerDias = DB::TABLE("USUARIO_INSCRITO")->get();

        foreach($obtenerDias as $dias){
            if($dias->DIAS_PENDIENTES==2){
                

            }
        }



    }
}
