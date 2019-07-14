<?php

require_once "Entidades/login.php";

class LoginApi extends Login {

    public function AltaDatos($request, $response, $args) {

        $objDelaRespuesta= new stdclass();
        
        $ArrayDeParametros = $request->getParsedBody();
        
        $usuario = $ArrayDeParametros['usuario'];
        $contrasenia = $ArrayDeParametros['contrasenia'];
        // $idempleado = $ArrayDeParametros['idempleado'];

        $usuario = new Login();
        $usuario->usuario = $usuario;
        $usuario->contrasenia = $contrasenia;
        $usuario->idempleado = $idempleado;
        
        $empleado = "";//TraerEmpleadoConId($idempleado);

        if($empleado->id != "" && $usuario != NULL && $contrasenia != NULL && $idempleado != NULL) {
            $id = $usuario->AltaDatos();
            $objDelaRespuesta->respuesta = "Se inserto el usuario numero: $id";
        } else {
            $objDelaRespuesta->respuesta = "Se necesita especificar usuario, contraseña e idempleado";
        }
        
        return $response->withJson($objDelaRespuesta, 200);
    }
}

?>