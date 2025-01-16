<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class descontarDias extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:descontar-dias';

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
        $fechas = DB::table('USUARIO_INSCRITO')->select("FECHA_REGRESIVA","ID_USUARIO","DIAS_PENDIENTES","FECHA_SIGUIENTE_PAGO")->get();
$diasPendientes = 0;
        foreach($fechas as $fecha){
            $idusuario = $fecha->ID_USUARIO;
            $fechaRegresiva = $fecha->FECHA_REGRESIVA;
            $fechaSiguientePago = $fecha->FECHA_SIGUIENTE_PAGO;
            $fechaNueva = date('Y-m-d', strtotime($fechaRegresiva . ' + 1 days'));
            $diasPendientes = (strtotime($fechaSiguientePago) - strtotime($fechaRegresiva)) / 86400;

            
            DB::table('USUARIO_INSCRITO')->where('ID_USUARIO',$idusuario )
            ->update(['FECHA_REGRESIVA' => $fechaNueva
            ,'DIAS_PENDIENTES' => $diasPendientes]
            );

        }

        $this->info('¡Días descontados!');


    }
}
