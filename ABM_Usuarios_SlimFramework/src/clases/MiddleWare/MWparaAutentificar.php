<?php

require_once "../src/clases/AutentificadorJWT.php";
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
					// $response = $next($request, $response);
				}
				else
				{
					$payload=AutentificadorJWT::ObtenerData($token);
					// var_dump($payload);
					
					echo "perfil ";
					var_dump($payload->perfil);
					//var_dump($payload);
					// DELETE,PUT y DELETE sirve para todos los logeados y admin
					if($payload->perfil == "admin")
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
				$objDelaRespuesta->respuesta="Solo usuarios registrados";
				$objDelaRespuesta->elToken=$token;
			}  		  
		if($objDelaRespuesta->respuesta!="")
		{
			$nueva=$response->withJson($objDelaRespuesta, 401);  
			return $nueva;
		}
		 return $response;   
	}
	public function VerificarUsuarioToken($request, $response,$next) {

		$peticion = $request->getParsedBody();
		$objDelaRespuesta = new stdclass();
		$token = "";
		$token = $request->getHeader('token')[0];	
		// if($request->isGet())
		// {
		// 	$token = $request->getHeader('token')[0];		
		// }

		if(isset($token) || $token!="")
		{
			$usuarioLogueado = AutentificadorJWT::ObtenerData($token);

			$usuario = $usuarioLogueado->usuario;

			$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
			$consulta = $objetoAccesoDato->RetornarConsulta("select * from usuario 
															where usuario = :user");
	
			$consulta->bindValue(':user', $usuario, PDO::PARAM_STR);
			$consulta->execute();	
			$usuario = $consulta->fetchObject("usuario");

			if($usuario != NULL || $usuario!=false) {
			   $response = $next($request,$response);
			   return $response;
			} else {
				$objDelaRespuesta->data = "Usuario Inexistente";
				return $response->withJson($objDelaRespuesta,401);
			}
		}
		else{
			$objDelaRespuesta->data = "Debe loguearse";
			return $response->withJson($objDelaRespuesta,401);
		}       
    }
}
