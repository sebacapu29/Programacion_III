<?php

require_once "../src/Entidades/AutentificadorJWT.php";
require_once "../src/Entidades/infoEmpleado.php";

class MWparaRegistroOperacion
{
    public function RegistrarOperacion($request,$response,$args){

        $peticion = $request->getParsedBody();
		$objDelaRespuesta = new stdclass();
        $token = "";
        $ruta = $request->getRequestTarget();
        $isLogin = strpos($ruta, "login")>0;
		$isAltaUser = strpos($ruta,"/empleado/alta")>0;
		
		 if($isLogin || $isAltaUser){
			$response = $next($request,$response);
			return $response;
		 }
		 else{
			$token = $request->getHeader('token')[0];
         }
         
		if(isset($token) || $token!="")
		{
			$usuarioLogueado = AutentificadorJWT::ObtenerData($token);
			$empleadoUsuario = $usuarioLogueado->usuario;
			$empleadoTipo = $usuarioLogueado->tipo;
			$idEmpleado = $usuarioLogueado->idempleado;

			$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
			$consulta = $objetoAccesoDato->RetornarConsulta("select * from empleado 
															where  id=:idempleado");
	
			$consulta->bindValue(':idempleado', $idEmpleado, PDO::PARAM_INT);
			$consulta->execute();	
            $empleado = $consulta->fetchObject("empleado");
            
            $infoEmpleado = new InfoEmpleado();

            $infoEmpleado->RegistrarOperacion($ruta,$idEmpleado);//insert a la tabla operacionempleado
            $response = $args($request,$response);
            return $response;
		}
		else
		{
			$objDelaRespuesta->data = "Debe loguearse";
			return $response->withJson($objDelaRespuesta,401);
		}
    }
}