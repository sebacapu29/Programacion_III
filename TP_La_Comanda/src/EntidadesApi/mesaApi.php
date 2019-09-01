<?php
require_once "../src/Entidades/mesa.php";
require_once "../src/Entidades/factura.php";
require_once "../src/Entidades/pedidoDetalle.php";
require_once "../src/Entidades/pedido.php";
require_once "../src/Entidades/comentario.php";

class MesaApi extends Mesa {

    public function InsertarMesa($request, $response, $args) {

        $objDelaRespuesta= new stdclass();
        $ArrayDeParametros = $request->getParsedBody();
        
        $sector = $ArrayDeParametros['sector'];
        switch($sector) {
            case "Cocina": 
                $sector = SectoresMesa::Cocina;
                break;
            case "BarraDeTragos": 
                $sector = SectoresMesa::BarraDeTragos;
                break;
            case "BarraDeCervezas": 
                $sector = SectoresMesa::BarraDeCervezas;
                break;
            case "CandyBar": 
                $sector = SectoresMesa::CandyBar;
                break;
            default: 
                $sector = NULL;
        }
        $estado = EstadosMesa::Cerrada;
        
        $miMesa = new Mesa();
        $miMesa->sector = $sector;
        $miMesa->estado = $estado;
        if($sector != NULL) {
            $id = $miMesa->AltaDeMesa();
            $objDelaRespuesta->respuesta = "Se inserto la mesa numero: $id";
        } else {
            $objDelaRespuesta->respuesta = "Se necesita especificar el sector [ Cocina / BarraDeTragos / BarraDeCervezas / CandyBar ]";
        }  
        return $response->withJson($objDelaRespuesta, 200);
    }

    public function BorrarMesa($request, $response, $args) {

        $objDelaRespuesta= new stdclass();
        $ArrayDeParametros = $request->getParsedBody();
        $mesa = $ArrayDeParametros['mesa'];
        $miMesa = Mesa::TraerMesaConId($mesa);
        $retornoConsulta =$miMesa->BajaDeMesa();
        if($retornoConsulta){
            $objDelaRespuesta->respuesta ="Se dió de baja la mesa: " . $mesa;
        }
        else{
            $objDelaRespuesta->respuesta ="Error al dar de baja la mesa: " . $mesa;    
        }
        return $response->withJson($objDelaRespuesta, 200);
    }

    public function ModificarMesa($request, $response, $args) {

        $objDelaRespuesta= new stdclass();
        $ArrayDeParametros = $request->getParsedBody();
        $mesa = $ArrayDeParametros['mesa'];
        $sector = $ArrayDeParametros['sector'];
        $estado = $ArrayDeParametros['estado'];
        $miMesa = Mesa::TraerMesaConId($mesa);
        $miMesa->sector = $sector;
        $miMesa->estado = $estado;
        $retornoConsulta=$miMesa->ModificacionDeMesa();

        if($retornoConsulta){
            $objDelaRespuesta->respuesta = "Se modificó la mesa: " . $mesa;
        }
        else{
            $objDelaRespuesta->respuesta = "Error al modificar mesa: " . $mesa;
        }
        return $response->withJson($objDelaRespuesta, 200);
    }
    public function AgregarComentario($request, $response, $args) {

        $objDelaRespuesta= new stdclass();
        $ArrayDeParametros = $request->getParsedBody();
        $puntaje = $ArrayDeParametros['puntaje'];
        $descripcion = $ArrayDeParametros['descripcion'];
        $idmesa = $ArrayDeParametros['idmesa'];
        
        $miComentario = new Comentario();
        $miComentario->puntaje = $puntaje;
        $miComentario->descripcion = $descripcion;
        $miComentario->idmesa = $idmesa;
        $respuesta = $items->AltaDeComentario();
        $objDelaRespuesta->respuesta = "Se inserto el comentario con id: $respuesta";
        return $response->withJson($objDelaRespuesta, 200);
    }

    public function ModificarComentario($request, $response, $args) {

        $objDelaRespuesta= new stdclass();
        $ArrayDeParametros = $request->getParsedBody();
        $id = $ArrayDeParametros['id'];
        $puntaje = $ArrayDeParametros['puntaje'];
        $descripcion = $ArrayDeParametros['descripcion'];
        $idmesa = $ArrayDeParametros['idmesa'];

        $miPedido = Pedido::TraerComentarioConId($id);
        $miPedido->puntaje = $puntaje;
        $miPedido->descripcion = $descripcion;
        $miPedido->idmesa = $idmesa;

        $objDelaRespuesta->respuesta = $miPedido->ModificacionDeComentario();

        return $response->withJson($objDelaRespuesta, 200);
    }

    public function BorrarComentario($request, $response, $args) {
        
        $objDelaRespuesta= new stdclass();
        $ArrayDeParametros = $request->getParsedBody();
        $id = $ArrayDeParametros['id'];

        $miPedido = Pedido::TraerComentarioConId($id);
        $objDelaRespuesta->respuesta = $miPedido->BajaDeComentario();

        return $response->withJson($objDelaRespuesta, 200);
    }

    public function Facturar($request, $response, $args) {

        $objDelaRespuesta= new stdclass();
        $ArrayDeParametros = $request->getParsedBody();
        $responsable = $ArrayDeParametros['responsable'];
        $mesa = $ArrayDeParametros['mesa'];
        $pedido = $ArrayDeParametros['pedido'];
        $importe = $ArrayDeParametros['importe'];

        $nuevaFactura = new Factura();
        $nuevaFactura->idresponsable = $responsable;
        $nuevaFactura->idpedido = $pedido;
        $nuevaFactura->idmesa = $mesa;
        $nuevaFactura->importe = $importe;
        
        $miMesa = Mesa::TraerMesaConId($mesa);
        $miMesa->estado = EstadosMesa::Pagando;

        if($mesa != NULL && $responsable != NULL  && $pedido != NULL && $importe != NULL && $miMesa->id != "" ) {
            $respuesta = $nuevaFactura->AltaDeFactura();
            $respuesta = ($miMesa->ModificacionDeMesa() . $respuesta); 
            $objDelaRespuesta->respuesta = "Se insertaron la factura con id: $respuesta"; 
        } else {
            $objDelaRespuesta->respuesta = "Se necesita especificar un los parametros (Responsable, Mesa, Pedido, Importe)";
        }
        return $response->withJson($objDelaRespuesta, 200);
    }

    public function CerrarMesa($request, $response, $args) {

        $objDelaRespuesta= new stdclass();
        $ArrayDeParametros = $request->getParsedBody();
        $mesa = $ArrayDeParametros['mesa'];
        $miMesa = Mesa::TraerMesaConId($mesa);
        $miMesa->estado = EstadosMesa::Cerrada;

        if($mesa != NULL && $miMesa->id != "" ) {
            $respuesta = $miMesa->ModificacionDeMesa();
            $objDelaRespuesta->respuesta = "Se cerro la mesa numero: $mesa"; 
        } else {
            $objDelaRespuesta->respuesta = "Se necesita especificar un numero de mesa valido";
        }

        return $response->withJson($objDelaRespuesta, 200);
    }
    public function MesaMasUsada($request,$response,$args){
        $respuesta = Mesa::MasUsada();
        $newResponse = $response->withJson($respuesta,200);
        return $newResponse;
    }
    
    public function MesaMenosUsada($request,$response,$args){
        $respuesta = Mesa::MenosUsada();
        $newResponse = $response->withJson($respuesta,200);
        return $newResponse;
    }
    
    public function MesaMasFacturacion($request,$response,$args){
        $respuesta = Mesa::MasFacturacion();
        $newResponse = $response->withJson($respuesta,200);
        return $newResponse;
    }
    
    public function MesaMenosFacturacion($request,$response,$args){
        $respuesta = Mesa::MenosFacturacion();
        $newResponse = $response->withJson($respuesta,200);
        return $newResponse;
    }

    public function FacturaConMasImporte($request,$response,$args){
        $respuesta = Mesa::FacturaConMasImporte();
        $newResponse = $response->withJson($respuesta,200);
        return $newResponse;
    }
    
    public function FacturaConMenosImporte($request,$response,$args){
        $respuesta = Mesa::FacturaConMenosImporte();
        $newResponse = $response->withJson($respuesta,200);
        return $newResponse;
    }
    
    public function MesaConMejorPuntuacion($request,$response,$args){
        $respuesta = Mesa::MesaConMejorPuntuacion();
        $newResponse = $response->withJson($respuesta,200);
        return $newResponse;
    }
    
    public function MesaConPeorPuntuacion($request,$response,$args){
        $respuesta = Mesa::MesaConPeorPuntuacion();
        $newResponse = $response->withJson($respuesta,200);
        return $newResponse;
    }
    
    public function FacturacionEntreFechas($request,$response,$args){
        $parametros = $request->getParsedBody();
        $codigoMesa = $parametros["codigo"];
        $fecha1 = $parametros["fecha1"];
        $fecha2 = $parametros["fecha2"];
        $respuesta = Mesa::FacturacionEntreFechas($codigoMesa,$fecha1,$fecha2);
        $newResponse = $response->withJson($respuesta,200);
        return $newResponse;
    }
}

?>