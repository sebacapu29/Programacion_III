<?php
include_once 'AccesoDatos.php';
include_once 'Usuario.php';

class Login {
    public $usuario;
    public $password;

    public static function _login($request,$response,$next){
        
        $objDelaRespuesta = new stdclass();

        $passwordParametero = $request->getParsedBody();
        // var_dump($passwordParametero->usuario);
        $user = $passwordParametero["usuario"];     
        $pass = $passwordParametero["password"];
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
        
        $consulta = $objetoAccesoDato->RetornarConsulta("select * from usuario 
                                                        where usuario = :user and password = :pass");

        $consulta->bindValue(':user', $user, PDO::PARAM_STR);
        $consulta->bindValue(':pass', $pass, PDO::PARAM_STR);
        $consulta->execute();	
        $usuario = $consulta->fetchObject("usuario");
        // var_dump($usuario);
        if($usuario!=false)
        {
            if($usuario->PASSWORD == $passwordParametero["password"])
            {
                $objDelaRespuesta->respuesta = "Bienvenido@ " . $passwordParametero["usuario"];
                $response= $response->withJson($objDelaRespuesta,200);
                // $response->getBody()->write("Bienvenido@ " . $passwordParametero["usuario"]);
                // $response = $next($request,$response);
            }
        }  
        else
        {
            $objDelaRespuesta->respuesta = "Usuario o Contraseña Incorrecta";
            // $newresponse=$response->withJson($objDelaRespuesta,401);
            $response = $response->withJson($objDelaRespuesta,500);
        }
        return $response;
    }
}

?>