<?php
require_once  '../src/Entidades/mesa.php';
require_once  '../src/Entidades/factura.php';
require_once  '../src/Entidades/pedidoDetalle.php';
require_once  '../src/Entidades/pedido.php';
require_once  '../src/Entidades/comentario.php';
require_once  '../src/Entidades/empleado.php';
require_once '../src/Entidades/AutentificadorJWT.php';

class PedidoApi extends Pedido {

    public function HacerPedido($request, $response, $args) {

        $objDelaRespuesta= new stdclass();     
        $ArrayDeParametros = $request->getParsedBody();
        $tiempoestimado = $ArrayDeParametros['tiempoestimado'];
        $mesa = (int)($ArrayDeParametros['mesa']);
        $pedido = $ArrayDeParametros['pedido'];
        $foto = "[Empty]";

        if(isset($_FILES['foto'])){
            // $foto = PedidoApi::GuardarFoto($_FILES['foto']);
        }
        $miPedidoDetalle = new PedidoDetalle();
        $miPedido = new Pedido();
        $miPedido->idmesa = $mesa;
        $miPedido->tiempoestimado = $tiempoestimado;
        $miPedido->foto = $foto;
        $miPedido->estado = Estados::EnPreparacion;
        $miMesa = Mesa::TraerMesaConId($mesa);
        if(!$miMesa){ $miMesa = new stdclass(); $miMesa->id=""; }
        $miMesa->estado = EstadosMesa::Esperando;       
        sleep(3); 

        if($tiempoestimado != NULL && $miMesa->id != "") {
            $codigo="";
            $codigo = $miPedido->AltaDePedido();
            PedidoApi::AltaDePedidoDetalle($pedido,$codigo);
            $miMesa->ModificacionDeMesa();
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
        $retornoConsulta =$miPedido->ModificacionDePedido();
        sleep(3);
        if($retornoConsulta){
            $objDelaRespuesta->respuesta = "Se Modoficó el pedido";
        }
        else{
            $objDelaRespuesta->respuesta = "Error al modificar el pedido";
        }

        return $response->withJson($objDelaRespuesta, 200);
    }

    public function TraerPedidos($request, $response, $args) {

        $objDelaRespuesta= new stdclass();
        $pedidos = Pedido::TraerTodosLosPedidos();
        $respuesta="<br>";
        foreach($pedidos as $aux) {
            $respuesta = $respuesta . $aux->MostrarDatosDelPedido() . "<br>";
        }
        $objDelaRespuesta->respuesta = $respuesta;
        return $response->withJson($objDelaRespuesta, 200);
    }
    public function TraerPedidosPorTipoEmpleado($request, $response, $args){
        $objDelaRespuesta= new stdclass();
        $pedidos=NULL;
        $respuesta="";
        $token = $request->getHeader('token')[0];
        $datos = AutentificadorJWT::ObtenerData($token);
        $empleado = Empleado::TraerEmpleadoConId((int)$datos->idempleado);

        switch($empleado->tipo) {
            case TipoDeEmpleado::Socio: 
                $pedidos=Pedido::TraerTodosLosPedidos();
                break;
            case TipoDeEmpleado::Mozo: 
                $pedidos=Pedido::TraerTodosLosPedidos();
                break;
            case TipoDeEmpleado::Cocinero: 
                $pedidos=Pedido::TraerPedidoPorSector(SectoresMesa::Cocina);
                break;
            case TipoDeEmpleado::Cervecero: 
                $pedidos=Pedido::TraerPedidoPorSector(SectoresMesa::BarraDeCervezas);
                break;
            case TipoDeEmpleado::Bartender: 
                $pedidos=Pedido::TraerPedidoPorSector(SectoresMesa::BarraDeTragos);
                break;
            default: 
                $pedidos = NULL;
        }
        if($pedidos!= NULL){
            foreach($pedidos as $aux) {
                $respuesta = $respuesta . $aux->MostrarDatosDelPedido() . "<br>";
            }
        }
        else{
            $respuesta = "Id Empleado Incorrecto";
        }
        $objDelaRespuesta->respuesta = $respuesta;
        return $response->withJson($objDelaRespuesta, 200);
    }
    public function PedidoListo($request, $response, $args) {

        $objDelaRespuesta= new stdclass();
        $ArrayDeParametros = $request->getParsedBody();
        $codigo = $ArrayDeParametros['codigo'];
        
        $miPedido = Pedido::TraerPedidoConCodigo($codigo);
        $miPedido->estado = Estados::ListoParaServir;
        sleep(3);
        if($codigo != NULL && $miPedido->id != "") {
            $respuesta = $miPedido->ModificacionDePedido();
            $objDelaRespuesta->respuesta = "Pedido listo: $codigo";  
        } else {
            $objDelaRespuesta->respuesta = "Se necesita especificar un pedido (Valido)";
        }

        return $response->withJson($objDelaRespuesta, 200);
    }

    public function PedidoEntregado($request, $response, $args) {

        $objDelaRespuesta= new stdclass();
        $ArrayDeParametros = $request->getParsedBody();
        $codigo = $ArrayDeParametros['codigo'];
        date_default_timezone_set('America/Argentina/Buenos_Aires');
        $tiempoentrega = date("H:i:s", time());

        $miPedido = Pedido::TraerPedidoConCodigo($codigo);
        $miPedido->tiempoentrega = $tiempoentrega;
        $miPedido->estado = Estados::Entregado;
        
        $miMesa = Mesa::TraerMesaConId($miPedido->idmesa);
        $miMesa->estado = EstadosMesa::Comiendo;

        if($tiempoentrega != NULL && $miMesa->id != "" && $miPedido->id != "") {
            $respuesta = $miPedido->ModificacionDePedido();
            $respuesta = ($miMesa->ModificacionDeMesa() . $respuesta); 
            $objDelaRespuesta->respuesta = "Pedido entregado: $codigo";    
        } else {
            $objDelaRespuesta->respuesta = "Se necesita especificar un pedido (Valido)";
        }

        return $response->withJson($objDelaRespuesta, 200);
    }
    public function CancelarPedido($request, $response, $args) {

        $objDelaRespuesta= new stdclass();
        $ArrayDeParametros = $request->getParsedBody();
        $codigo = $ArrayDeParametros['codigo'];

        $miPedido = Pedido::TraerPedidoConCodigo($codigo);
        $miPedido->estado = Estados::Cancelado;
        sleep(2);
        if($miPedido->id != "") {
            $respuesta = $miPedido->ModificacionDePedido();
            $objDelaRespuesta->respuesta = "Pedido Cancelado: $codigo";    
        } else {
            $objDelaRespuesta->respuesta = "Se necesita especificar un pedido valido";
        }

        return $response->withJson($objDelaRespuesta, 200);
    }
}   
?>