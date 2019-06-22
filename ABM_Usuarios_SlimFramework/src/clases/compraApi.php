<?php

include_once 'AutentificadorJWT.php';
include_once 'compra.php';

class CompraApi extends Compra{
    
    public function ConsultarCompra($request,$response,$args){
        $objDeLaRespuesta = new stdClass();
        $token = $request->getHeader("token")[0];

        $usuario = AutentificadorJWT::ObtenerData($token);

        $listaCompras = Compra::RetornarCompra($usuario->perfil,$usuario->usuario);

        return $response->withJson($listaCompras,200);
    }
    public static function GestionCompra($request,$response,$args)
    {
            $peticion = $request->getParsedBody();
            $objDeLaRespuesta = new stdClass();
            $nuevaCompra = new Compra();
            $nuevaCompra->articulo =$peticion["articulo"];
            $nuevaCompra->precio =$peticion["precio"];
            $nuevaCompra->fecha =$peticion["fecha"];
            $foto = $_FILES["foto"];
            $token = $peticion["token"];

            $usuarioLogueado = AutentificadorJWT::ObtenerData($token);
            $nuevaCompra->usuario = $usuarioLogueado->usuario; 

            $objDeLaRespuesta = $nuevaCompra->RealizarCompra($foto);
      
            if($objDeLaRespuesta->inserto){
                return $response->withJson($objDeLaRespuesta,200);
            }	
            else{
                return $response->withJson($objDeLaRespuesta,500);
            }
            return $response;
    }
}

?>