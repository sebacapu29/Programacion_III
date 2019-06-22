<?php

include_once '../src/clases/AccesoDatos.php';

class MWGuardarInfoEnDB{

    public static function GuardarDatosEnDB($request,$response,$next){

        $ruta = $request->getRequestTarget();
        $body = $request->getParsedBody();
        $metodo = $request->getMethod();
        $token = $body['token'];
        $fecha =date('d-m-Y');
        $usuario = 'user';

        if( $request->isGet()){
			$token =$request->getHeader('token')[0];
        }
        if(isset($token) || $token!='' || $token!=NULL){
            $usuarioLogueado = AutentificadorJWT::ObtenerData($token);
            $usuario = $usuarioLogueado->usuario;    
        }
        else{
            $usuario= $request->getParam("usuario");       
        }
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
        $consulta =$objetoAccesoDato->RetornarConsulta("INSERT into infoUsuario (usuario,metodo,fecha,ruta)values(:usuario,:metodo,:fecha,:ruta)");
        $consulta->bindValue(':usuario', $usuario, PDO::PARAM_STR);
        $consulta->bindValue(':metodo', $metodo, PDO::PARAM_STR);
        $consulta->bindValue(':fecha', $fecha, PDO::PARAM_STR);
        $consulta->bindValue(':ruta', $ruta, PDO::PARAM_STR);
        $consulta->execute();		

        $response = $next($request,$response);
        return $response;
    }
}

?>