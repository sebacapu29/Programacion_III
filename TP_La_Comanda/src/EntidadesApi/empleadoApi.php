<?php

require_once "../src/Entidades/empleado.php";
require_once "../src/Entidades/horarioEmpleados.php";
require_once "../src/Entidades/login.php";
require_once "../src/Entidades/infoEmpleado.php";

class EmpleadoApi extends Empleado {

    public function InsertarEmpleado($request, $response, $args) {

        $objDelaRespuesta= new stdclass();
        
        $ArrayDeParametros = $request->getParsedBody();
        
        $tipo = $ArrayDeParametros['tipo'];
        
        switch($tipo) {
            case "Socio": 
                $tipo = TipoDeEmpleado::Socio;
                break;
            case "Mozo": 
                $tipo = TipoDeEmpleado::Mozo;
                break;
            case "Cocinero": 
                $tipo = TipoDeEmpleado::Cocinero;
                break;
            case "Cervecero": 
                $tipo = TipoDeEmpleado::Cervecero;
                break;
            case "Bartender": 
                $tipo = TipoDeEmpleado::Bartender;
                break;
            default: 
                $tipo = 0;
        }
        $estado = EstadoEmpleado::Activo;
        
        $empleado = new Empleado();
        $empleado->tipo = $tipo;
        $empleado->estado = $estado;

        if($empleado->tipo != 0) {
            $id = $empleado->AltaDeEmpleado();
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
        $clave = $ArrayDeParametros['clave'];
        $existe = Empleado::ObtenerEmpleadoUsuario($usuario, $clave);
        $infoEmpleado = new InfoEmpleado();
        $infoEmpleado->idempleado = $idempleado;
        if($idempleado != NULL && $existe == true) {
            $id = $infoEmpleado->FichajeEmpleado();
            $objDelaRespuesta->respuesta = "Se registro el empleado: " . date('d-m-y');
        } else {
            $objDelaRespuesta->respuesta = "Se necesita especificar el empleado";
        }
        return $response->withJson($objDelaRespuesta, 200);
    }

    public function Suspender($request, $response, $args) {
        
        $objDelaRespuesta= new stdclass();        
        $ArrayDeParametros = $request->getParsedBody();        
        $idempleado = $ArrayDeParametros['idempleado'];

        $empleado = Empleado::TraerEmpleadoConId($idempleado);
        $empleado->estado = EstadoEmpleado::Suspendido;

        if($idempleado != NULL && $empleado->id != "") {
            $empleado->ModificacionDeEmpleado();
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

        $empleado = Empleado::TraerEmpleadoConId($idempleado);
        $empleado->estado = EstadoEmpleado::Inactivo;

        if($idempleado != NULL && $empleado->id != "") {
            $empleado->ModificacionDeEmpleado();
            $objDelaRespuesta->respuesta = "Se borro al empleado";
        } else {
            $objDelaRespuesta->respuesta = "Se necesita especificar el empleado (Valido)";
        }
        return $response->withJson($objDelaRespuesta, 200);
    }

    public function BorrarEmpleado($request, $response, $args) {
        
        $objDelaRespuesta= new stdclass();
        $ArrayDeParametros = $request->getParsedBody();
        $id = (int)$ArrayDeParametros['idempleado'];

        $miEmpleado = Empleado::TraerEmpleadoConId($id);
        $objDelaRespuesta->respuesta = 'Se elimino: '  . $miEmpleado->BorradoDeEmpleado() . 'Empleado';

        return $response->withJson($objDelaRespuesta, 200);
    }

    public function TraerEmpleados($request,$response,$args){       
        $objDelaRespuesta= new stdclass();
        $empleados = Empleado::TraerTodosLosEmpleados();

        foreach ($empleados as $empleado) {
            $empleado->clave = '******';
        }
        return $response->withJson($empleados, 200);
    }
    public function ModificarEmpleado($request, $response, $args) {
        
        $objDelaRespuesta= new stdclass();
        $ArrayDeParametros = $request->getParsedBody();
        $id = $ArrayDeParametros['id'];
        $estado = $ArrayDeParametros['estado'];
        $tipo = $ArrayDeParametros['tipo'];

        $miEmpleado = Empleado::TraerEmpleadoConId($id);
        $miEmpleado->estado = $estado;
        $miEmpleado->tipo = $tipo;
        $objDelaRespuesta->respuesta = $miPedido->ModificacionDeEmpleado();

        return $response->withJson($objDelaRespuesta, 200);
    }
    public function TraerEmpleado($request, $response, $args) {
        
        $objDelaRespuesta= new stdclass();
        $ArrayDeParametros = $request->getParsedBody();
        $estado = $ArrayDeParametros['usuario'];
        $tipo = $ArrayDeParametros['clave'];

        $miEmpleado = Empleado::TraerEmpleadoConId($id);
        $miEmpleado->estado = $estado;
        $miEmpleado->tipo = $tipo;
        $objDelaRespuesta->respuesta = $miPedido->ModificacionDeEmpleado();

        return $response->withJson($objDelaRespuesta, 200);
    }
    public function TraerInfoEmpleadoDias($request, $response, $args) {
        
        $objDelaRespuesta= new stdclass();

        $infoEmpleados = InfoEmpleado::ObtenerDiasYHorarios();

        $objDelaRespuesta->respuesta = 'Info Empleados';
        $objDelaRespuesta->data = $infoEmpleados;

        return $response->withJson($objDelaRespuesta, 200);
    }
    public function OperacionesPorSector($request, $response, $args) {
        
        $objDelaRespuesta= new stdclass();

        $infoEmpleados = InfoEmpleado::TraerOperacionesPorSector();

        $objDelaRespuesta->respuesta = 'Info Empleados';
        $objDelaRespuesta->data = $infoEmpleados;

        return $response->withJson($objDelaRespuesta, 200);
    }
    public function OperacionesPorSectorPorEmpleados($request, $response, $args) {
        
        $objDelaRespuesta= new stdclass();

        $infoEmpleados = InfoEmpleado::TraerOperacionesPorSectorPorEmpleados();

        $objDelaRespuesta->respuesta = 'Info Empleados';
        $objDelaRespuesta->data = $infoEmpleados;

        return $response->withJson($objDelaRespuesta, 200);
    }
    public function OperacionesEmpleados($request, $response, $args) {
        
        $objDelaRespuesta= new stdclass();

        $infoEmpleados = InfoEmpleado::TraerOperacionesEmpleados();

        $objDelaRespuesta->respuesta = 'Info Empleados';
        $objDelaRespuesta->data = $infoEmpleados;

        return $response->withJson($objDelaRespuesta, 200);
    }
}

?>