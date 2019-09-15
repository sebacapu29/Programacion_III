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
        $miusuario->InsertarElUsuario();

        $usuarioToken = new stdclass();

        $usuarioToken->usuario =$usuario;
        $usuarioToken->sexo = $sexo;
        $usuarioToken->perfil = $miusuario->perfil;
    
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
  public function ModificarUsuario($request, $response, $args){
    $objDelaRespuesta= new stdclass();
    $ArrayDeParametros = $request->getParsedBody();
    $objUsuarioModificar = new Usuario();
    $id = (int) $ArrayDeParametros["id"];
    $usuario = $ArrayDeParametros["usuario"] ? $ArrayDeParametros["usuario"] : null;
    $clave = $ArrayDeParametros["clave"];
    $sexo = $ArrayDeParametros["sexo"];
    $perfil = $ArrayDeParametros["perfil"];
    $usuarioActual=null;

    if(isset($id)){
      $usuarioActual = Usuario::TraerUsuarioPorId($id);
      var_dump($usuarioActual);
    }
    if($usuario!=null){
      $usuarioActual->usuario = $usuario;
    }
    if(isset($clave)){
      $usuarioActual->clave = $clave;
    }
    if(isset($sexo)){
      $usuarioActual->sexo = $sexo;
    }
    if(isset($tipo)){
      $usuarioActual->perfil = $perfil;
    }
    $objDelaRespuesta->data = $usuarioActual->Modificar();
    return $response ->withJson($objDelaRespuesta,200);
  }
  public function BorrarUsuario($request, $response, $args){
    $objDelaRespuesta= new stdclass();
    $ArrayDeParametros = $request->getParsedBody();

    if(isset($ArrayDeParametros["id"])){
      $objDelaRespuesta->data =Usuario::Borrar($ArrayDeParametros["id"]);
      return $response ->withJson($objDelaRespuesta,200);
    }
    $objDelaRespuesta->data ="error";
    return $response ->withJson($objDelaRespuesta,500);
    }
}
?>