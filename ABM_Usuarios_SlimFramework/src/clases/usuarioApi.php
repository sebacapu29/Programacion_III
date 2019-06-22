<?php
include_once '../src/clases/IApiUsable.php';
include_once '../src/clases/AutentificadorJWT.php';

class UsuarioApi extends Usuario implements IApiUsable{
    public function AltaUsuario($request, $response, $args) {
     	
        $objDelaRespuesta= new stdclass();
        
        $ArrayDeParametros = $request->getParsedBody();
        //var_dump($ArrayDeParametros);
        $usuario= $ArrayDeParametros['usuario'];
        $clave= $ArrayDeParametros['clave'];
        $sexo= $ArrayDeParametros['sexo'];
                
        $miusuario = new usuario();
       
        $miusuario->usuario=$usuario;
        $miusuario->clave=$clave;
        $miusuario->sexo=$sexo;
        $miusuario->perfil = "usuario";
        $miusuario->InsertarElUsuarioParametros();

        $usuarioToken = new stdclass();

        $usuarioToken->usuario =$usuario;
        $usuarioToken->sexo = $sexo;
        $usuarioToken->perfil = $miusuario->perfil;
        // $archivos = $request->getUploadedFiles();
        // $destino="./fotos/";
        //var_dump($archivos);
        //var_dump($archivos['foto']);
        // if(isset($archivos['foto']))
        // {
        //     $nombreAnterior=$archivos['foto']->getClientFilename();
        //     $extension= explode(".", $nombreAnterior)  ;
        //     //var_dump($nombreAnterior);
        //     $extension=array_reverse($extension);
        //     $archivos['foto']->moveTo($destino.$titulo.".".$extension[0]);
        // }       
        //$response->getBody()->write("se guardo el cd");
        $objDelaRespuesta->respuesta="Se guardo el usuario.";   
        $token = AutentificadorJWT::CrearToken($usuarioToken);
        $objDelaRespuesta->token = $token;
        return $objDelaRespuesta;
    }
    public function TraerTodos($request, $response, $args) {
        $objDelaRespuesta= new stdclass();
        $objDelaRespuesta->data =Usuario::TraerTodoLosusuarios();
      return $response ->withJson($objDelaRespuesta,200);
  }
}
?>