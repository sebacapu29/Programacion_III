<?php

require_once "Entidades/empleado.php";
require_once "Entidades/horarioEmpleados.php";
require_once "Entidades/login.php";

class EmpleadoApi extends Empleado {

    public function InsertarEmpleado($request, $response, $args) {

        $objDelaRespuesta= new stdclass();
        
        $ArrayDeParametros = $request->getParsedBody();
        
        $tipo = $ArrayDeParametros['tipo'];
        
        switch($tipo) {
            case "Socio": 
                $tipo = TipoDePersonal::Socio;
                break;
            case "Mozo": 
                $tipo = TipoDePersonal::Mozo;
                break;
            case "Cocinero": 
                $tipo = TipoDePersonal::Cocinero;
                break;
            case "Cervecero": 
                $tipo = TipoDePersonal::Cervecero;
                break;
            case "Bartender": 
                $tipo = TipoDePersonal::Bartender;
                break;
            default: 
                $tipo = 0;
        }
        $estado = EstadoPersonal::Activo;
        
        $empleado = new Personal();
        $empleado->tipo = $tipo;
        $empleado->estado = $estado;

        if($empleado->tipo != 0) {
            $id = $empleado->AltaDePersonal();
            $objDelaRespuesta->respuesta = "Se inserto el empleado numero: $id";
        } else {
            $objDelaRespuesta->respuesta = "Se necesita especificar el tipo [ Socio / Mozo / Cocinero / Cervecero / Bartender ]";
        }
        
        return $response->withJson($objDelaRespuesta, 200);
    }

    public function Fichar($request, $response, $args) {

        $objDelaRespuesta= new stdclass();        
        $ArrayDeParametros = $request->getParsedBody();        
        $idempleado = $ArrayDeParametros['idempleado'];
        $usuario = $ArrayDeParametros['usuario'];
        $contrasenia = $ArrayDeParametros['contrasenia'];
        $existe = Login::Verificar($usuario, $contrasenia);
        $horario = new Horarios();
        $horario->idempleado = $idempleado;
        if($idempleado != NULL && $existe == true) {
            $id = $horario->AltaDeHorario();
            $objDelaRespuesta->respuesta = "Se registro el horario: " . $horario->fecha;
        } else {
            $objDelaRespuesta->respuesta = "Se necesita especificar el empleado";
        }
        return $response->withJson($objDelaRespuesta, 200);
    }

    public function Suspender($request, $response, $args) {
        
        $objDelaRespuesta= new stdclass();        
        $ArrayDeParametros = $request->getParsedBody();        
        $idempleado = $ArrayDeParametros['idempleado'];

        $empleado = Personal::TraerEmpleadoConId($idempleado);
        $empleado->estado = EstadoPersonal::Suspendido;

        if($idempleado != NULL && $empleado->id != "") {
            $empleado->ModificacionDePersonal();
            $objDelaRespuesta->respuesta = "Se suspendio al empleado";
        } else {
            $objDelaRespuesta->respuesta = "Se necesita especificar el empleado (Valido)";
        }
        return $response->withJson($objDelaRespuesta, 200);
    }

    public function BajaLogica($request, $response, $args) {
        
        $objDelaRespuesta= new stdclass();        
        $ArrayDeParametros = $request->getParsedBody();        
        $idempleado = $ArrayDeParametros['idempleado'];

        $empleado = Personal::TraerEmpleadoConId($idempleado);
        $empleado->estado = EstadoPersonal::Inactivo;

        if($idempleado != NULL && $empleado->id != "") {
            $empleado->ModificacionDePersonal();
            $objDelaRespuesta->respuesta = "Se borro al empleado";
        } else {
            $objDelaRespuesta->respuesta = "Se necesita especificar el empleado (Valido)";
        }
        return $response->withJson($objDelaRespuesta, 200);
    }

    public function BorrarEmpleado($request, $response, $args) {
        
        $objDelaRespuesta= new stdclass();
        $ArrayDeParametros = $request->getParsedBody();
        $id = $ArrayDeParametros['id'];

        $miEmpleado = Personal::TraerEmpleadoConId($id);
        $objDelaRespuesta->respuesta = $miEmpleado->BajaDePersonal();

        return $response->withJson($objDelaRespuesta, 200);
    }

    public function ModificarEmpleado($request, $response, $args) {
        
        $objDelaRespuesta= new stdclass();
        $ArrayDeParametros = $request->getParsedBody();
        $id = $ArrayDeParametros['id'];
        $estado = $ArrayDeParametros['estado'];
        $tipo = $ArrayDeParametros['tipo'];

        $miEmpleado = Personal::TraerEmpleadoConId($id);
        $miEmpleado->estado = $estado;
        $miEmpleado->tipo = $tipo;
        $objDelaRespuesta->respuesta = $miPedido->ModificacionDePersonal();

        return $response->withJson($objDelaRespuesta, 200);
    }
}

?>