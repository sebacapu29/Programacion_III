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
        $usuario = $ArrayDeParametros['usuario'];
        $clave = $ArrayDeParametros['clave'];
        $nombre = $ArrayDeParametros['nombre'];        
        $apellido = $ArrayDeParametros['apellido'];
        $sector = $ArrayDeParametros['sector'];        
        
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
    
        $empleado = new Empleado();
        $empleado->tipo = $tipo;
        $empleado->usuario = $usuario;
        $empleado->nombre = $nombre;
        $empleado->apellido = $apellido;
        $empleado->clave = $clave;
        $empleado->sector = $sector;
        $empleado->estado =  EstadoEmpleado::Activo;

        if($empleado->tipo != 0 && isset($empleado->nombre) && isset($empleado->apellido) 
        || isset($empleado->clave) && isset($empleado->sector) && isset($empleado->usuario)) {
            $id = $empleado->AltaDeEmpleado();
            $objDelaRespuesta->respuesta = "Se inserto el empleado numero: $id";
        } else {
            $objDelaRespuesta->respuesta = "Se necesita especificar el tipo [ Socio / Mozo / Cocinero / Cervecero / Bartender ] Tipo, Usuario, Nombre, Clave y Sector";
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
            $objDelaRespuesta->respuesta = "Se registro el empleado: " . date('Y-m-d');
        } else {
            $objDelaRespuesta->respuesta = "Se necesita especificar el empleado";
        }
        return $response->withJson($objDelaRespuesta, 200);
    }

    public function Suspender($request, $response, $args) {
        
        
        $objDelaRespuesta= new stdclass();        
        $ArrayDeParametros = $request->getParsedBody();        
        $idempleado = $ArrayDeParametros['idempleado'];
        
        $empleadoSuspendido = new Empleado();
        $empleado = Empleado::TraerEmpleadoConId($idempleado);

        if($idempleado != NULL && $empleado[0]->id != "") {
            $empleadoSuspendido->id = $empleado[0]->id;
            $empleadoSuspendido->usuario = $empleado[0]->usuario;
            $empleadoSuspendido->clave = $empleado[0]->clave;
            $empleadoSuspendido->tipo = $empleado[0]->tipo;
            $empleadoSuspendido->estado =  EstadoEmpleado::Suspendido;
            $empleadoSuspendido->nombre = $empleado[0]->nombre;
            $empleadoSuspendido->apellido = $empleado[0]->apellido;
            $empleadoSuspendido->sector = $empleado[0]->sector;
             $empleadoSuspendido->fechaingreso = $empleado[0]->fechaingreso;
             
            $empleadoSuspendido->CambiarEstadoEmpleado();
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
        $miEmpleado = new Empleado();
        $miEmpleado = Empleado::TraerEmpleadoConId($id)[0];
        $objDelaRespuesta->respuesta = 'Se elimino: '  . $miEmpleado->BorradoDeEmpleado() . ' Empleado';

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
        $id = $ArrayDeParametros['idempleado'];
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