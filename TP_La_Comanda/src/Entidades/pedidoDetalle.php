<?php

class pedidoDetalle {

    // public $id;
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
        $consulta = $objetoAccesoDato->RetornarConsulta("SELECT p.id, m.descripcion, count(p.id) as cantidad_ventas
                                                            FROM pedidodetalle p INNER JOIN menu m
                                                            ON m.descripcion = p.descripcion GROUP BY(descripcion) HAVING count(p.descripcion) = 
                                                            (SELECT MAX(sel.cantidad_ventas) FROM 
                                                            (SELECT count(p.id) as cantidad_ventas FROM pedidodetalle p GROUP BY(descripcion)) sel);");

        //$consulta->bindValue(':codigo',$codigo, PDO::PARAM_STR);
		$consulta->execute();
		//$pedido = $consulta->fetchObject('Pedido');
		return $consulta->fetchAll();
    }
    public static function TraerPedidosMenosVendidos(){
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
        $consulta = $objetoAccesoDato->RetornarConsulta("SELECT p.id, m.descripcion, count(p.id) as cantidad_ventas
                                                            FROM pedidodetalle p INNER JOIN menu m
                                                            ON m.descripcion = p.descripcion GROUP BY(descripcion) HAVING count(p.descripcion) = 
                                                            (SELECT MIN(sel.cantidad_ventas) FROM 
                                                            (SELECT count(p.id) as cantidad_ventas FROM pedidodetalle p GROUP BY(descripcion)) sel);");
        //$consulta->bindValue(':codigo',$codigo, PDO::PARAM_STR);
		$consulta->execute();
		//$pedido = $consulta->fetchObject('Pedido');
		return $consulta->fetchAll();
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