<?php

class pedidoDetalle {

    public $id;
    public $cantidad;
    public $descripcion;
    public $codigopedido;

    public function AltaDePedidoDetalle() {
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
        $consulta = $objetoAccesoDato->RetornarConsulta("INSERT into pedidoDetalle (cantidad,descripcion,codigopedido)values(:cantidad,:descripcion,:codigopedido)");
        $consulta->bindValue(':cantidad', $this->cantidad, PDO::PARAM_INT);
        $consulta->bindValue(':descripcion', $this->descripcion, PDO::PARAM_STR);
        $consulta->bindValue(':codigopedido', $this->codigopedido, PDO::PARAM_STR);
        $consulta->execute();		
        return $objetoAccesoDato->RetornarUltimoIdInsertado();
    }

    public function BajaDeItem() {
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
        $consulta = $objetoAccesoDato->RetornarConsulta("DELETE from pedidoDetalle WHERE id=:id");	
        $consulta->bindValue(':id', $this->id, PDO::PARAM_INT);		
        $consulta->execute();
        return $consulta->rowCount();
    }

    public function ModificacionDeItem() {
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
        $consulta = $objetoAccesoDato->RetornarConsulta("UPDATE items set cantidad=:cantidad, descripcion=:descripcion, codigopedido=:codigopedido WHERE id=:id");
        $consulta->bindValue(':id', $this->id, PDO::PARAM_INT);
        $consulta->bindValue(':cantidad', $this->cantidad, PDO::PARAM_INT);
        $consulta->bindValue(':descripcion', $this->descripcion, PDO::PARAM_STR);
        $consulta->bindValue(':codigopedido', $this->codigopedido, PDO::PARAM_STR);
        return $consulta->execute();
    }
    public function TraerTodosLosPedidoDetalle() {
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
        $consulta = $objetoAccesoDato->RetornarConsulta("SELECT * from pedidodetalle order by descripcion");	
        // $consulta->bindValue(':id', $this->id, PDO::PARAM_INT);		
        $consulta->execute();
        return $consulta->rowCount();
    }
    public static function TraerPedidosMasVendidos(){
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
        $objDeLaRespuesta = new stdclass();
        $consulta = $objetoAccesoDato->RetornarConsulta("SELECT p.id, p.descripcion, SUM(p.cantidad) as cantidad_ventas
                                                         FROM PedidoDetalle p GROUP BY(descripcion) HAVING SUM(p.cantidad) = (SELECT MAX(sel.cantidad_ventas) 
                                                         FROM (SELECT SUM(p.cantidad) as cantidad_ventas 
                                                         FROM PedidoDetalle p GROUP BY(descripcion) HAVING p.descripcion like 'Menu%') sel)");

        //$consulta->bindValue(':codigo',$codigo, PDO::PARAM_STR);
		$consulta->execute();
		//$pedido = $consulta->fetchObject('Pedido');
		$pedidodetalle = $consulta->fetchAll();
		 $objDeLaRespuesta->id = $pedidodetalle[0][0];
		 $objDeLaRespuesta->Menu = $pedidodetalle[0][1];
		 $objDeLaRespuesta->Cantidad_Ventas = $pedidodetalle[0][2];
		return $objDeLaRespuesta;
    }
    public static function TraerPedidosMenosVendidos(){
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
        $objDeLaRespuesta = new stdclass();
        $consulta = $objetoAccesoDato->RetornarConsulta("SELECT p.id, p.descripcion, SUM(p.cantidad) as cantidad_ventas 
                                                         FROM PedidoDetalle p GROUP BY(descripcion) HAVING SUM(p.cantidad) = (SELECT MIN(sel.cantidad_ventas) 
                                                         FROM (SELECT SUM(p.cantidad) as cantidad_ventas 
                                                         FROM PedidoDetalle p GROUP BY(descripcion) HAVING p.descripcion like 'Menu%') sel)");
        //$consulta->bindValue(':codigo',$codigo, PDO::PARAM_STR);
		$consulta->execute();
		//$pedido = $consulta->fetchObject('Pedido
			$pedidodetalle = $consulta->fetchAll();
		 $objDeLaRespuesta->id = $pedidodetalle[0][0];
		 $objDeLaRespuesta->Menu = $pedidodetalle[0][1];
		 $objDeLaRespuesta->Cantidad_Ventas = $pedidodetalle[0][2];
		return $objDeLaRespuesta;
    }
    public static function ActualizarPedidosVendidos(){
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
        $consulta = $objetoAccesoDato->RetornarConsulta("SELECT MIN(cantidad),descripcion from pedidosvendidos");
        $consulta->bindValue(':codigo',$codigo, PDO::PARAM_STR);
		$consulta->execute();
		$pedido = $consulta->fetchObject('Pedido');
		return $pedido;
    }
}

?>