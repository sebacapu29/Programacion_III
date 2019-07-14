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
                
        $miusuario = new usuario();
       
        $miusuario->usuario=$usuario;
        $miusuario->clave=$clave;
        $miusuario->perfil = "usuario";
        $miusuario->InsertarElUsuarioParametros();

        $usuarioToken = new stdclass();

        $usuarioToken->usuario =$usuario;
        $usuarioToken->perfil = $miusuario->perfil;

        $objDelaRespuesta->respuesta="Se guardo el usuario.";   
        $token = AutentificadorJWT::CrearToken($usuarioToken);
        $objDelaRespuesta->token = $token;
        return $objDelaRespuesta;
    }
    public function TraerTodos($request, $response, $args) {
        $todosLosUsuarios=Usuario::TraerTodoLosusuarios();
       $newresponse = $response->withJson($todosLosUsuarios, 200);  
      return $newresponse;
  }
}
?>