<?php
include_once '../src/AccesoDatos/AccesoDatos.php';
include_once 'Usuario.php';
include_once 'AutentificadorJWT.php';

class Login {

    public static function _login($request,$response,$next){
        
        $objDelaRespuesta = new stdclass();

        $passwordParametero = $request->getParsedBody();
        $user = $passwordParametero["usuario"];     
        $pass = $passwordParametero["clave"];
        // $idTipo = $passwordParametero["idempleado"];

        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
        
        $consulta = $objetoAccesoDato->RetornarConsulta("select * from usuario 
                                                        where usuario = :user 
                                                        and clave = :pass ");

        $consulta->bindValue(':user', $user, PDO::PARAM_STR);
        $consulta->bindValue(':pass', $pass, PDO::PARAM_STR);
        // $consulta->bindValue(':id', $idTipo, PDO::PARAM_STR);

        $consulta->execute();	
        $usuario = $consulta->fetchObject("usuario");
        
        if($usuario!=false)
        {
            $usuarioToken = new stdclass();
            $usuarioToken->usuario = $usuario->usuario;
            $usuarioToken->idempleado = $usuario->idempleado;

            if($usuario->clave == $passwordParametero["clave"])
            {
                $objDelaRespuesta->respuesta = "Bienvenido@ " . $passwordParametero["usuario"];
                $token = AutentificadorJWT::CrearToken($usuarioToken);
                $objDelaRespuesta->token = $token;
            }
        }  
        else
        {
            $objDelaRespuesta->respuesta = "La clave o el idTipo o el nombre no existe";
            $objDelaRespuesta->status = 500;            
        }
        return $objDelaRespuesta;
    }
}

?>