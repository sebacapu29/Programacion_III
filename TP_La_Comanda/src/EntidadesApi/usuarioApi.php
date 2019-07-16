<?php
include_once '../src/Interfaces/IApiUsuario.php';
include_once '../src/Entidades/AutentificadorJWT.php';

class UsuarioApi extends Usuario implements IApiUsuario{
    public function AltaUsuario($request, $response, $args) {
     	
        $objDelaRespuesta= new stdclass();
        
        $ArrayDeParametros = $request->getParsedBody();
        $usuario= $ArrayDeParametros['usuario'];
        $clave= $ArrayDeParametros['clave'];
        $idempleado= $ArrayDeParametros['idempleado'];
                
        $miusuario = new Usuario();
       
        $miusuario->usuario=$usuario;
        $miusuario->clave=$clave;
        $miusuario->idempleado = $idempleado;
        $miusuario->InsertarElUsuarioParametros();

        $usuarioToken = new stdclass();

        $usuarioToken->usuario =$usuario;
        $usuarioToken->idempleado = $idempleado;

        $objDelaRespuesta->respuesta="Se guardo el usuario.";   
        $token = AutentificadorJWT::CrearToken($usuarioToken);
        $objDelaRespuesta->token = $token;    
        return $response->withJson($objDelaRespuesta,200);;
    }
    public function TraerTodos($request, $response, $args) {
        $todosLosUsuarios=Usuario::TraerTodoLosusuarios();
       $newresponse = $response->withJson($todosLosUsuarios, 200);  
      return $newresponse;
  }
}
?>