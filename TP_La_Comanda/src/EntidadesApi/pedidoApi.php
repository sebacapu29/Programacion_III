<?php
require_once  '../src/Entidades/mesa.php';
require_once  '../src/Entidades/factura.php';
require_once  '../src/Entidades/pedidoDetalle.php';
require_once  '../src/Entidades/pedido.php';
require_once  '../src/Entidades/comentario.php';
// require_once '../vendor/kicken/gearman-php/src/Client.php';

class PedidoApi extends Pedido {

    public function HacerPedido($request, $response, $args) {

        $objDelaRespuesta= new stdclass();     
        $ArrayDeParametros = $request->getParsedBody();
        $tiempoestimado = $ArrayDeParametros['tiempoestimado'];
        $mesa = (int)($ArrayDeParametros['mesa']);
        $pedido = $ArrayDeParametros['pedido'];
        $foto = "[Empty]";
        // set_time_limit(315);
        if(isset($_FILES['foto'])){
            // $foto = PedidoApi::GuardarFoto($_FILES['foto']);
        }
        $miPedidoDetalle = new PedidoDetalle();
        $miPedido = new Pedido();
        $miPedido->idmesa = $mesa;
        $miPedido->tiempoestimado = $tiempoestimado;
        $miPedido->foto = $foto;
        $miMesa = Mesa::TraerMesaConId($mesa);
        $miMesa->estado = EstadosMesa::Esperando;  
        sleep(3); 

        if($tiempoestimado != NULL && $miMesa->id != "") {
            $codigo="";
            // $codigo = $miPedido->AltaDePedido();
            // PedidoApi::AltaDePedidoDetalle($pedido,$codigo);
            // $miMesa->ModificacionDeMesa();
            $objDelaRespuesta->respuesta = "Pedido dado de Alta: Codigo de pedido: $codigo";    
        } else {
            $objDelaRespuesta->respuesta = "Se necesita especificar una mesa (Valida) y el tiempo estimado (HH:MM:SS)";
        }
        return $response->withJson($objDelaRespuesta, 200);
    }
    public static function GuardarFoto($file)
    {
        $name = $file['name'];
        $tmp_name = $file['tmp_name'];
        $pathFoto =   __DIR__ . "/../../Fotos/";
        $date = date('d-m-Y');
        $sNombre = explode(".",$name);
        move_uploaded_file($tmp_name, $pathFoto. $sNombre[0] . "_" . $date . '.' .$sNombre[1]);
        return  $sNombre[0] . "_" . $date . '.' .$sNombre[1];
    }
    private static function AltaDePedidoDetalle($pedido,$codigo){
        
        $arrayPlatos = explode(',',$pedido);
    
        foreach ($arrayPlatos as $pedidoItem) {
            
            $auxPedido = trim($pedidoItem);
            $miPedidoDetalle = new PedidoDetalle();
            $pedidoCantidad = preg_split('/[^0-9]/', $auxPedido);
            $pedidoDescripcion = preg_replace('/[0-9]+/', '', $auxPedido);
            $cantidad = $pedidoCantidad[0];
            $descripcion = trim($pedidoDescripcion);
            $miPedidoDetalle->cantidad = (int)$cantidad;
            $miPedidoDetalle->descripcion = $descripcion;
            $miPedidoDetalle->codigopedido = $codigo;
            
            $miPedidoDetalle->AltaDePedidoDetalle();
     }
    }
     public function BorrarPedido($request, $response, $args) {
        
        $objDelaRespuesta= new stdclass();
        $ArrayDeParametros = $request->getParsedBody();
        $codigo = $ArrayDeParametros['codigo'];

        $miPedido = Pedido::TraerPedidoConCodigo($codigo);
        $objDelaRespuesta->respuesta = $miPedido->BajaDePedido();

        return $response->withJson($objDelaRespuesta, 200);
    }
    
    public function ModificarPedido($request, $response, $args) {
        
        $objDelaRespuesta= new stdclass();
        $ArrayDeParametros = $request->getParsedBody();
        $estado = $ArrayDeParametros['estado'];
        $tiempoestimado = $ArrayDeParametros['tiempoestimado'];
        $tiempoentrega = $ArrayDeParametros['tiempoentrega'];
        $codigo = $ArrayDeParametros['codigo'];
        $idmesa = $ArrayDeParametros['idmesa'];
        $foto = $ArrayDeParametros['foto'];

        $miPedido = Pedido::TraerPedidoConCodigo($codigo);
        $miPedido->estado = $estado;
        $miPedido->tiempoestimado = $tiempoestimado;
        $miPedido->tiempoentrega = $tiempoentrega;
        $miPedido->idmesa = $idmesa;
        $miPedido->foto = $foto;
        $objDelaRespuesta->respuesta = $miPedido->ModificacionDePedido();

        return $response->withJson($objDelaRespuesta, 200);
    }

    public function TraerPedidos($request, $response, $args) {

        $objDelaRespuesta= new stdclass();
        $pedidos = Pedido::TraerTodosLosPedidos();
        foreach($pedidos as $aux) {
            $respuesta = $respuesta . $aux->mostrarDatosDelPedido() . "<br>";
        }
        $objDelaRespuesta->respuesta = $respuesta;
        return $response->withJson($objDelaRespuesta, 200);
    }
}   
?>