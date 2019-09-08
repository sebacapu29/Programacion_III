<?php

require_once "../src/Entidades/AutentificadorJWT.php";
require_once "../src/Entidades/infoEmpleado.php";

class MWparaRegistroOperacion
{
      ///Suma una operación al empleado.
	  public static function IncrementarOperacionAEmpleado($request, $response, $next)
	  {
		  $arrayConToken = $request->getHeader('token');
		  $resourceUri = "/" . $request->getUri()->getPath() . " [". $request->getMethod() ."]";
		  $token=$arrayConToken[0];
		  $payload = AutentificadorJWT::ObtenerPayLoad($token);
		  Empleado::IncrementarOperacion($payload->data->idempleado,$resourceUri);
		  return $next($request, $response);
	  }
}