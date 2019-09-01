<?php

require_once "../src/Entidades/AutentificadorJWT.php";
class MWparaAutentificar
{
 /**
   * @api {any} /MWparaAutenticar/  Verificar Usuario
   * @apiVersion 0.1.0
   * @apiName VerificarUsuario
   * @apiGroup MIDDLEWARE
   * @apiDescription  Por medio de este MiddleWare verifico las credeciales antes de ingresar al correspondiente metodo 
   *
   * @apiParam {ServerRequestInterface} request  El objeto REQUEST.
 * @apiParam {ResponseInterface} response El objeto RESPONSE.
 * @apiParam {Callable} next  The next middleware callable.
   *
   * @apiExample Como usarlo:
   *    ->add(\MWparaAutenticar::class . ':VerificarUsuario')
   */
	public function VerificarUsuario($request, $response, $next) {
         
		$objDelaRespuesta= new stdclass();
		$objDelaRespuesta->respuesta="";
		
		if($request->isGet())
		{
		// $response->getBody()->write('<p>NO necesita credenciales para los get </p>');
		 $response = $next($request, $response);
		}
		else
		{
			$token= AutentificadorJWT::CrearToken("");
			$objDelaRespuesta->esValido=true; 
			try 
			{
				//$token="";
				AutentificadorJWT::verificarToken($token);
				$objDelaRespuesta->esValido=true;      
			}
			catch (Exception $e) {      
				//guardar en un log
				$objDelaRespuesta->excepcion=$e->getMessage();
				$objDelaRespuesta->esValido=false;     
			}

			if($objDelaRespuesta->esValido)
			{						
				if($request->isPost())
				{		
					// el post sirve para todos los logeados			    
					$response = $next($request, $response);
				}
				else
				{
					$payload=AutentificadorJWT::ObtenerData($token);
					//var_dump($payload);
					// DELETE,PUT y DELETE sirve para todos los logeados y admin
					if($payload->perfil=="Administrador")
					{
						$response = $next($request, $response);
					}		           	
					else
					{	
						$objDelaRespuesta->respuesta="Solo administradores";
					}
				}		          
			}    
			else
			{
				//   $response->getBody()->write('<p>no tenes habilitado el ingreso</p>');
				$objDelaRespuesta->respuesta="Solo usuarios registrados";
				$objDelaRespuesta->elToken=$token;

			}  
		}		  
		if($objDelaRespuesta->respuesta!="")
		{
			$nueva=$response->withJson($objDelaRespuesta, 401);  
			return $nueva;
		}
		  
		 //$response->getBody()->write('<p>vuelvo del verificador de credenciales</p>');
		 return $response;   
	}
	public function VerificarUsuarioToken($request, $response,$next) {
		$peticion = $request->getParsedBody();
		$objDelaRespuesta = new stdclass();
		$token="";
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
			$usuario = $usuarioLogueado->usuario;
			$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
			$consulta = $objetoAccesoDato->RetornarConsulta("select * from empleado 
															where usuario = :user");
	
			$consulta->bindValue(':user', $usuario, PDO::PARAM_STR);
			$consulta->execute();	
			$usuario = $consulta->fetchObject("empleado");
			if($usuario != NULL || $usuario!=false) {
			   $response = $next($request,$response);
			   return $response;
			} else {
				$objDelaRespuesta->data = "Usuario Inexistente";
				return $response->withJson($objDelaRespuesta,401);
			}
		}
		else
		{
			$objDelaRespuesta->data = "Debe loguearse";
			return $response->withJson($objDelaRespuesta,401);
		}
	}

	public static function VerificarUsuarioAdmin($request,$response,$next){

		$peticion = $request->getParsedBody();
		$objDelaRespuesta = new stdclass();
		$token = $request->getHeader('token')[0];

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

			if(($empleado != NULL || $empleado!=false) && $empleado->tipo==6) {

					$response = $next($request,$response);
					return $response;				
			} else {
				$objDelaRespuesta->data = "No tiene permisos para realizar la operación";
				return $response->withJson($objDelaRespuesta,401);
			}
		}
		else
		{
			$objDelaRespuesta->data = "Debe loguearse";
			return $response->withJson($objDelaRespuesta,401);
		}
	}
}