<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

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
        // Obtén los registros
        $fechas = DB::table('USUARIO_INSCRITO')
            ->select("FECHA_REGRESIVA", "ID_USUARIO", "DIAS_PENDIENTES", "FECHA_SIGUIENTE_PAGO")
            ->get();
    
        foreach ($fechas as $fecha) {
            $idusuario = $fecha->ID_USUARIO;
    
            // Asegúrate de manejar las fechas correctamente
            $fechaRegresiva = Carbon::parse($fecha->FECHA_REGRESIVA);
            $fechaSiguientePago = Carbon::parse($fecha->FECHA_SIGUIENTE_PAGO);
            

    
            // Incrementa la fecha regresiva en 1 día
            $fechaRegresiva->addDay();
            


    
            // Calcula los días pendientes
            $diasPendientes = $fechaRegresiva->diffInDays($fechaSiguientePago);
    
            // Actualiza la tabla con las nuevas fechas
            DB::table('USUARIO_INSCRITO')->where('ID_USUARIO', $idusuario)
                ->update([
                    'FECHA_REGRESIVA' => $fechaRegresiva->toDateString(), // Asegúrate de guardar como string
                    'DIAS_PENDIENTES' => $diasPendientes,
                ]);
        }
    
        $this->info('¡Días descontados!');
    }
    
}
