<?php
include_once '../src/AccesoDatos/AccesoDatos.php';
include_once 'Empleado.php';
include_once 'AutentificadorJWT.php';

class Login {

    public static function _login($request,$response,$next){
        
        $objDelaRespuesta = new stdclass();

        $passwordParametero = $request->getParsedBody();
        $user = $passwordParametero["usuario"];     
        $pass = $passwordParametero["clave"];
        // $idTipo = $passwordParametero["idempleado"];

        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
        
        $consulta = $objetoAccesoDato->RetornarConsulta("select * from empleado 
                                                        where usuario = :user 
                                                        and clave = :pass ");

        $consulta->bindValue(':user', $user, PDO::PARAM_STR);
        $consulta->bindValue(':pass', $pass, PDO::PARAM_STR);
         //$consulta->bindValue(':id', $idTipo, PDO::PARAM_STR);

        $consulta->execute();	
        $empleado = $consulta->fetchObject("empleado");
        
        if($empleado!=false)
        {
            $usuarioToken = new stdclass();
            $usuarioToken->usuario = $empleado->usuario;
            $usuarioToken->tipo = $empleado->tipo;
            $usuarioToken->idempleado = $empleado->id;

            if($empleado->clave == $passwordParametero["clave"])
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