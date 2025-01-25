<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use DateTime;
use Carbon\Carbon;



class gymController extends Controller
{
    //

    
    public function getUsuarios(){
        $usuarios=DB::table('USUARIO')->get();

        return response()->json($usuarios);
    }

    public function getTipoInscripcion(){
        $modos=DB::table('TIPO_INSCRIPCION')->select("NOMBRE","PRECIO","ID_TIPO_INSCRIPCION")->get();
        $plan=DB::table('TIPO_PLAN')->select("NOMBRE_PLAN","PRECIO","ID_TIPO_PLAN")->get();


        return response()->json(['modos'=>$modos,'plan'=>$plan]);    
    }	

    public function guardarCliente(Request $request){
        //corroborar que todos los campos no sean null. Nombre, telefono, tipo_inscripcion, fecha_inscripcion
        $nombre=$request->input('nombre');
        $telefono=$request->input('telefono');
        $tipo_inscripcion=$request->input('tipoInscripcion');
        $tipo_plan=$request->input('tipoPlan');
        $fecha_inscripcion=$request->input('fechaInscripcion');
        $formaPago=$request->input('formaPago');

        if($nombre==null || $telefono==null || $tipo_inscripcion==null || $fecha_inscripcion==null){
            return response()->json(['success' => false, 'message' => 'Faltan campos por llenar','color'=>'text-red-500'], 500);

        }

        $planesYPrecios=DB::table('TIPO_PLAN')->where('ID_TIPO_PLAN',$tipo_plan)->first();
        $tipos_inscripcion=DB::table('TIPO_INSCRIPCION')->where('ID_TIPO_INSCRIPCION',$tipo_inscripcion)->first();
     
        $id_usuario = DB::table('USUARIO')->insertGetId([
            'NOMBRE_COMPLETO' => $nombre,
            'NUMERO_TELEFONO' => $telefono,
            'ID_TIPO_PLAN' => $tipo_plan,
            'ID_TIPO_INSCRIPCION' => $tipo_inscripcion,
            'FECHA_INSCRIPCION' => $fecha_inscripcion
        ]);

        $getUsuario = DB::table('USUARIO')->where('ID_USUARIO', $id_usuario)->first();


        switch($tipo_inscripcion){
            case 1:
                
                $fechaInscripcionMes = new DateTime($fecha_inscripcion);

        // Tomar el día original
        $dia = $fechaInscripcionMes->format('d');

        // Avanzar al siguiente mes
        $fechaSiguientePago = $fechaInscripcionMes->modify('+1 month');

        // Verificar si el día existe en el siguiente mes
        $ultimoDiaDelMes = $fechaSiguientePago->format('t'); // Último día del mes
        if ($dia > $ultimoDiaDelMes) {
            // Si el día no existe, ajustamos al último día del mes
            $fechaSiguientePago->setDate(
                $fechaSiguientePago->format('Y'),
                $fechaSiguientePago->format('m'),
                $ultimoDiaDelMes
            );
        } else {
            // Si el día existe, configuramos la misma fecha
            $fechaSiguientePago->setDate(
                $fechaSiguientePago->format('Y'),
                $fechaSiguientePago->format('m'),
                $dia
            );
        }

        // Formatear la fecha
        $fechaSiguientePago = $fechaSiguientePago->format('Y-m-d');
                
                    switch($tipo_plan){
                        case 1:
                       $insertarRegistro= DB::table("REGISTROS")->INSERT([
                            "DESCRIPCION"=>"Pago de mensualidad normal de ".$getUsuario->NOMBRE_COMPLETO,
                            "ID_TIPO_INGRESO"=>1,
                            "ID_TIPO_TRANSACCION"=>$formaPago,
                            "FECHA_REGISTRO"=>$fecha_inscripcion,
                            "MONTO"=>$planesYPrecios->PRECIO
                        ]);
                        break;
                        case 2:
                            $insertarRegistro= DB::table("REGISTROS")->INSERT([
                                "DESCRIPCION"=>"Pago de mensualidad Con plan de alimentacion de ".$getUsuario->NOMBRE_COMPLETO,
                                "ID_TIPO_INGRESO"=>1,
                                "ID_TIPO_TRANSACCION"=>$formaPago,
                                "FECHA_REGISTRO"=>$fecha_inscripcion,
                                "MONTO"=>$planesYPrecios->PRECIO
                            ]);
                            break;
                        case 3:
                            $insertarRegistro= DB::table("REGISTROS")->INSERT([
                                "DESCRIPCION"=>"Pago de mensualidad Con plan de Personal trainer de ".$getUsuario->NOMBRE_COMPLETO,
                                "ID_TIPO_INGRESO"=>1,
                                "ID_TIPO_TRANSACCION"=>$formaPago,
                                "FECHA_REGISTRO"=>$fecha_inscripcion,
                                "MONTO"=>$planesYPrecios->PRECIO
                            ]);
                            break;
                        
                        case 4:
                            $insertarRegistro= DB::table("REGISTROS")->INSERT([
                                "DESCRIPCION"=>"Pago de mensualidad Con plan completo de ".$getUsuario->NOMBRE_COMPLETO,
                                "ID_TIPO_INGRESO"=>1,
                                "ID_TIPO_TRANSACCION"=>$formaPago,
                                "FECHA_REGISTRO"=>$fecha_inscripcion,
                                "MONTO"=>$planesYPrecios->PRECIO
                            ]);
                            break;

                    }
                    break;
           
            case 2:
                $fechaSiguientePago=date('Y-m-d',strtotime($fecha_inscripcion."+ 2 week"));
                //solo hay plan para mensualidad. Aqui quiero unicamente agregar el registro.
                $insertarRegistro= DB::table("REGISTROS")->INSERT([
                    "DESCRIPCION"=>"Pago quincenal de ".$getUsuario->NOMBRE_COMPLETO,
                    "ID_TIPO_INGRESO"=>1,
                    "ID_TIPO_TRANSACCION"=>$formaPago,
                    "FECHA_REGISTRO"=>$fecha_inscripcion,
                    "MONTO"=>$tipos_inscripcion->PRECIO
                ]);
                break;
            case 3:
              $fechaSiguientePago=date('Y-m-d',strtotime($fecha_inscripcion."+ 1 week"));
                //solo hay plan para mensualidad. Aqui quiero unicamente agregar el registro.
                $insertarRegistro= DB::table("REGISTROS")->INSERT([
                    "DESCRIPCION"=>"Pago semanal de ".$getUsuario->NOMBRE_COMPLETO,
                    "ID_TIPO_INGRESO"=>1,
                    "ID_TIPO_TRANSACCION"=>$formaPago,
                    "FECHA_REGISTRO"=>$fecha_inscripcion,
                    "MONTO"=>$tipos_inscripcion->PRECIO
                ]);

                break;
            case 4:
                $fechaSiguientePago=date('Y-m-d',strtotime($fecha_inscripcion."+ 1 day"));
                //solo hay plan para mensualidad. Aqui quiero unicamente agregar el registro.
                $insertarRegistro= DB::table("REGISTROS")->INSERT([
                    "DESCRIPCION"=>"Pago diario de ".$getUsuario->NOMBRE_COMPLETO,
                    "ID_TIPO_INGRESO"=>1,
                    "ID_TIPO_TRANSACCION"=>$formaPago,
                    "FECHA_REGISTRO"=>$fecha_inscripcion,
                    "MONTO"=>$tipos_inscripcion->PRECIO
                ]);
                break;
            case 5:
                $fechaSiguientePago=null;
                break;
            default:
                return response()->json(['success' => false, 'message' => 'Tipo de inscripcion no valido','color'=>'text-red-500'], 500);
        }

        if ($fechaSiguientePago) {
            $fechaInscripcion = new DateTime($fecha_inscripcion);
            $fechaSiguiente = new DateTime($fechaSiguientePago);
        
            // Calculamos la diferencia en días
            $diferencia = $fechaInscripcion->diff($fechaSiguiente)->days;
        }

        
        DB::table('USUARIO_INSCRITO')->insert([
            'ID_USUARIO' => $id_usuario,
            'FECHA_REGRESIVA' => $fecha_inscripcion,
            'FECHA_SIGUIENTE_PAGO' => $fechaSiguientePago,
            'DIAS_PENDIENTES' => $diferencia,
            'MOROSO' => 0
        ]);
        
        DB::table('PAGOS')->insert([
            'ID_USUARIO' => $id_usuario,
            'FECHA_PAGO' => $fecha_inscripcion,
            'PRIMER_PAGO' => 1
        ]);
        
        return response()->json([
            'success' => true,
            'message' => 'Usuario registrado correctamente',
            'color' => 'text-green-500'
        ], 200);
        
    }

    public function getUsuariosInscritos(){

        $usuarios=DB::table('USUARIO_INSCRITO')->leftjoin("USUARIO as user", "user.ID_USUARIO",'=','USUARIO_INSCRITO.ID_INSCRIPCION')
        ->leftjoin("TIPO_INSCRIPCION as tipo","tipo.ID_TIPO_INSCRIPCION",'=','USUARIO_INSCRITO.ID_TIPO_INSCRIPCION')
        ->select('user.NOMBRE_COMPLETO','user.FECHA_INSCRIPCION','user.ID_USUARIO','user.NUMERO_TELEFONO','tipo.NOMBRE as Tipo_inscripcion','tipo.ID_TIPO_INSCRIPCION','USUARIO_INSCRITO.FECHA_REGRESIVA','USUARIO_INSCRITO.FECHA_SIGUIENTE_PAGO','USUARIO_INSCRITO.DIAS_PENDIENTES','USUARIO_INSCRITO.MOROSO')
        ->get();
        return response()->json($usuarios);

    }

    public function actualizarCliente(Request $request){
        $id_usuario=$request->input('id');
        $nombre=$request->input('nombre');
        $telefono=$request->input('telefono');
        $tipo_inscripcion=$request->input('tipoInscripcion');
        $fecha_inscripcion=$request->input('fechaInscripcion');
        $fecha_inscripcion=date('Y-m-d',strtotime($fecha_inscripcion));
        $tipo_plan=$request->input('tipoPlan');

        if($nombre==null || $telefono==null || $tipo_inscripcion==null || $fecha_inscripcion==null){
            return response()->json(['success' => false, 'message' => 'Faltan campos por llenar','color'=>'text-red-500'], 500);

        }

        switch($tipo_inscripcion){
            case 1:
               $fechaSiguientePago=date('Y-m-d',strtotime($fecha_inscripcion."+ 1 month"));
                break;
            case 2:
                $fechaSiguientePago=date('Y-m-d',strtotime($fecha_inscripcion."+ 1 week"));
                break;
            case 3:
                $fechaSiguientePago=null;
                break;
            case 4:
                $fechaSiguientePago=null;
                break;
            case 5:
                $fechaSiguientePago=null;
                break;
            default:
                return response()->json(['success' => false, 'message' => 'Tipo de inscripcion no valido','color'=>'text-red-500'], 500);
        }

        if ($fechaSiguientePago) {
            $fechaInscripcion = new DateTime($fecha_inscripcion);
            $fechaSiguiente = new DateTime($fechaSiguientePago);
        
            // Calculamos la diferencia en días
            $diferencia = $fechaInscripcion->diff($fechaSiguiente)->days;
        }else{
            $diferencia=0;
        }
        
        DB::table('USUARIO')->where('ID_USUARIO', $id_usuario)->update([
            'NOMBRE_COMPLETO' => $nombre,
            'NUMERO_TELEFONO' => $telefono,
            'ID_TIPO_PLAN' => $tipo_plan,
            'ID_TIPO_INSCRIPCION' => $tipo_inscripcion,
            'FECHA_INSCRIPCION' => $fecha_inscripcion
        ]);
        
        DB::table('USUARIO_INSCRITO')->where('ID_USUARIO', $id_usuario)->update([
            'FECHA_REGRESIVA' => $fecha_inscripcion,
            'FECHA_SIGUIENTE_PAGO' => $fechaSiguientePago,
            'DIAS_PENDIENTES' => $diferencia,
            'MOROSO' => 0
        ]);
        
        DB::table('PAGOS')->where('ID_USUARIO', $id_usuario)->where('PRIMER_PAGO', 1)->update([
            'FECHA_PAGO' => $fecha_inscripcion
        ]);
        
        return response()->json([
            'success' => true,
            'message' => 'Usuario actualizado correctamente',
            'color' => 'text-green-500'
        ], 200);
        
    }

    public function getTipos(){
        $tipoTransaccion = db::table("TIPO_TRANSACCION")->get();

        $tipoIngreso = db::table("TIPO_INGRESOS")->get();

        return response()->json(['success' => true, 'transaccion' =>  $tipoTransaccion,'tipoIngreso'=> $tipoIngreso], 200);

    }

    public function postRegistro(Request $request){
        $descripcion = $request->descripcion;
        $ingresoegreso = $request->ingresoEgreso;
        $tipoTransaccion = $request->transaccion;
        $fecha = Carbon::now();
        $monto=$request->monto;

        if($descripcion==null ||  
        $ingresoegreso==null ||
        $tipoTransaccion==null ||
        $monto==null){
            return response()->json(['error' => true, 'mensaje' => "Faltan campos por llenar"], 500);
        }

        

        $insertarRegistro = db::table("REGISTROS")->INSERT([
            "DESCRIPCION"=>$descripcion,
            "ID_TIPO_INGRESO"=>$ingresoegreso,
            "ID_TIPO_TRANSACCION"=>$tipoTransaccion,
            "FECHA_REGISTRO"=>$fecha,
            "MONTO"=>$monto
        ]);

        if($insertarRegistro){
            return response()->json(['success' => true, 'mensaje' => "Registro ingresado correctamente"], 200);
    
        }else{
            return response()->json(['error' => true, 'mensaje' => "Ha ocurrido un error"], 500);
     
        }
    }

    public function getRegistros(){



        $registros = db::table("REGISTROS")
        ->join("TIPO_INGRESOS","TIPO_INGRESOS.ID_TIPO_INGRESO","=","REGISTROS.ID_TIPO_INGRESO")
        ->join("TIPO_TRANSACCION","TIPO_TRANSACCION.ID_TIPO_TRANSACCION","=","REGISTROS.ID_TIPO_TRANSACCION")
        ->select("REGISTROS.*","TIPO_INGRESOS.NOMBRE_INGRESO as ingresoEgreso","TIPO_TRANSACCION.NOMBRE_TRANSACCION as transaccion")
        ->get();

        $totalBanco=0;
        $totalCaja=0;

        foreach($registros as $registro){
            $registro->cambioColor = $registro->ID_TIPO_INGRESO == 1 ? "color:green" : "color:red";
    
            if($registro->ID_TIPO_INGRESO==1 && $registro->ID_TIPO_TRANSACCION==1){
                $totalCaja+=$registro->MONTO;
            }else if($registro->ID_TIPO_INGRESO==1 && $registro->ID_TIPO_TRANSACCION==2){
                $totalBanco+=$registro->MONTO;
            }else if($registro->ID_TIPO_INGRESO==2 && $registro->ID_TIPO_TRANSACCION==1){
                $totalCaja-=$registro->MONTO;
            }else if($registro->ID_TIPO_INGRESO==2 && $registro->ID_TIPO_TRANSACCION==2){
                $totalBanco-=$registro->MONTO;
            }



    }

    

    return response()->json(['registros'=>$registros,'totalBanco'=>$totalBanco,'totalCaja'=>$totalCaja]);


}

public function filtroFechas(Request $request){
    $idFiltro = $request->idFiltro;
    $startOfWeek = Carbon::now()->startOfWeek(); // Inicio de la semana
$endOfWeek = Carbon::now()->endOfWeek();     // Fin de la semana
    $totalBanco=0;
    $totalCaja=0;
    $registros = db::table("REGISTROS")
    ->join("TIPO_INGRESOS","TIPO_INGRESOS.ID_TIPO_INGRESO","=","REGISTROS.ID_TIPO_INGRESO")
    ->join("TIPO_TRANSACCION","TIPO_TRANSACCION.ID_TIPO_TRANSACCION","=","REGISTROS.ID_TIPO_TRANSACCION")
    ->select("REGISTROS.*","TIPO_INGRESOS.NOMBRE_INGRESO as ingresoEgreso","TIPO_TRANSACCION.NOMBRE_TRANSACCION as transaccion");

    switch($idFiltro){
        case 1:
            $registros = $registros->whereDate("FECHA_REGISTRO",Carbon::now())->get();
  

            break;
        case 2:
            $registros = $registros  ->whereBetween('FECHA_REGISTRO', [$startOfWeek, $endOfWeek])
            ->get();;
            break;
       
        case 3:
            $registros = $registros->whereMonth("FECHA_REGISTRO",Carbon::now()->month)->get();
            break;
        case 4:
            $registros = $registros->whereYear("FECHA_REGISTRO",Carbon::now()->year)->get();
            break;

}


foreach($registros as $registro){
    $registro->cambioColor = $registro->ID_TIPO_INGRESO == 1 ? "color:green" : "color:red";

    if($registro->ID_TIPO_INGRESO==1 && $registro->ID_TIPO_TRANSACCION==1){
        $totalCaja+=$registro->MONTO;
    }else if($registro->ID_TIPO_INGRESO==1 && $registro->ID_TIPO_TRANSACCION==2){
        $totalBanco+=$registro->MONTO;
    }else if($registro->ID_TIPO_INGRESO==2 && $registro->ID_TIPO_TRANSACCION==1){
        $totalCaja-=$registro->MONTO;
    }else if($registro->ID_TIPO_INGRESO==2 && $registro->ID_TIPO_TRANSACCION==2){
        $totalBanco-=$registro->MONTO;
    }




}
return response()->json(['registros'=>$registros,'totalBanco'=>$totalBanco,'totalCaja'=>$totalCaja]);


}
}