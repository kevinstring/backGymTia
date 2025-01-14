<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use DateTime;


class gymController extends Controller
{
    //

    
    public function getUsuarios(){
        $usuarios=DB::table('USUARIO')->get();

        return response()->json($usuarios);
    }

    public function getTipoInscripcion(){
        $modos=DB::table('TIPO_INSCRIPCION')->select("NOMBRE","PRECIO","ID_TIPO_INSCRIPCION")->get();


        return response()->json($modos);    
    }	

    public function guardarCliente(Request $request){
        //corroborar que todos los campos no sean null. Nombre, telefono, tipo_inscripcion, fecha_inscripcion
        $nombre=$request->input('nombre');
        $telefono=$request->input('telefono');
        $tipo_inscripcion=$request->input('tipoInscripcion');
        $fecha_inscripcion=$request->input('fechaInscripcion');

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
        }
        
        $id_usuario=DB::table('USUARIO')->insertGetId(['NOMBRE_COMPLETO'=>$nombre,'NUMERO_TELEFONO'=>$telefono,'FECHA_INSCRIPCION'=>$fecha_inscripcion]);
        DB::table('USUARIO_INSCRITO')->insert(['ID_USUARIO'=>$id_usuario,'ID_TIPO_INSCRIPCION'=>$tipo_inscripcion,'FECHA_REGRESIVA'=>$fecha_inscripcion
        ,'FECHA_SIGUIENTE_PAGO'=>$fechaSiguientePago,'DIAS_PENDIENTES'=>$diferencia,'MOROSO'=>0]);
     DB::TABLE(table: "PAGOS")->insert(['ID_USUARIO'=>$id_usuario,'FECHA_PAGO'=>$fecha_inscripcion,'PRIMER_PAGO'=>1]);

        return response()->json(['success' => true, 'message' => 'Usuario registrado correctamente','color'=>'text-green-500'], 200);
    //ssss

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
        
        DB::table('USUARIO')->where('ID_USUARIO',$id_usuario)->update(['NOMBRE_COMPLETO'=>$nombre,'NUMERO_TELEFONO'=>$telefono,'FECHA_INSCRIPCION'=>$fecha_inscripcion]);
        DB::table('USUARIO_INSCRITO')->where('ID_USUARIO',$id_usuario)->update(['ID_TIPO_INSCRIPCION'=>$tipo_inscripcion,'FECHA_REGRESIVA'=>$fecha_inscripcion
        ,'FECHA_SIGUIENTE_PAGO'=>$fechaSiguientePago,'DIAS_PENDIENTES'=>$diferencia,'MOROSO'=>0]);
        DB::TABLE(table: "PAGOS")->where('ID_USUARIO', $id_usuario)->where("PRIMER_PAGO",1 )->update(['FECHA_PAGO'=>$fecha_inscripcion]);

        return response()->json(['success' => true, 'message' => 'Usuario actualizado correctamente','color'=>'text-green-500'], 200);
    }


}
