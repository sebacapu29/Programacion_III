<?php
// include_once '../src/Interfaces/IApiUsuario.php';
// include_once '../src/Entidades/AutentificadorJWT.php';

// class UsuarioApi extends Usuario implements IApiUsuario{
//     public function AltaUsuario($request, $response, $args) {
     	
//         $objDelaRespuesta= new stdclass();
        
//         $ArrayDeParametros = $request->getParsedBody();
//         $usuario= $ArrayDeParametros['usuario'];
//         $clave= $ArrayDeParametros['clave'];
//         $idempleado= $ArrayDeParametros['idempleado'];
                
//         $miusuario = new Usuario();
       
//         $miusuario->usuario=$usuario;
//         $miusuario->clave=$clave;
//         $miusuario->idempleado = $idempleado;
//         $miusuario->InsertarElUsuarioParametros();

//         $usuarioToken = new stdclass();

//         $usuarioToken->usuario =$usuario;
//         $usuarioToken->idempleado = $idempleado;

//         $objDelaRespuesta->respuesta="Se guardo el usuario.";   
//         $token = AutentificadorJWT::CrearToken($usuarioToken);
//         $objDelaRespuesta->token = $token;    
//         return $response->withJson($objDelaRespuesta,200);;
//     }
//     public function BorrarUsuario($request, $response, $args) {
     	
//         $objDelaRespuesta= new stdclass();
        
//         $ArrayDeParametros = $request->getParsedBody();
//         $idempleado= $ArrayDeParametros['idempleado'];
                
//         $miusuario = new Usuario();
       
//         $miusuario->idempleado = $idempleado;
//         $resultadoConsulta = $miusuario->BorrarUsuarioPorId();

//         if($resultadoConsulta){
//             $objDelaRespuesta->respuesta="Se eliminó el usuario: " . $idempleado;   
//         }
//         else{
//             $objDelaRespuesta->respuesta="Error al eliminar el usuario: " . $idempleado;   
//         }
//         return $response->withJson($objDelaRespuesta,200);;
//     }
//     public function ModificacionUsuario($request, $response, $args) {
     	
//         $objDelaRespuesta= new stdclass();
        
//         $ArrayDeParametros = $request->getParsedBody();
//         $clave= $ArrayDeParametros['clave'];
//         $usuario= $ArrayDeParametros['usuario'];
//         $idempleado= $ArrayDeParametros['idempleado'];
  
                
//         $miusuario = new Usuario();
       
//         $miusuario->idempleado = $idempleado;
//         $miusuario->clave = $clave;
//         $miusuario->usuario = $usuario;
//         $resultadoConsulta = $miusuario->ModificarUsuarioParametros();

//         if($resultadoConsulta){
//             $objDelaRespuesta->respuesta="Se modificó el usuario: " . $idempleado;   
//         }
//         else{
//             $objDelaRespuesta->respuesta="Error al modificar el usuario: " . $idempleado;   
//         }
//         return $response->withJson($objDelaRespuesta,200);;
//     }
//     public function TraerTodos($request, $response, $args) {
//         $todosLosUsuarios=Usuario::TraerTodoLosusuarios();
//        $newresponse = $response->withJson($todosLosUsuarios, 200);  
//       return $newresponse;
//   }
  
// }
?>