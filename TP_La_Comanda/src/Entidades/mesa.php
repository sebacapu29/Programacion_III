<?php

class Mesa {

    public $id;
    public $sector;
    public $estado; 
    
    public function AltaDeMesa() {
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
		$consulta = $objetoAccesoDato->RetornarConsulta("INSERT into mesa (sector,estado)values(:sector,:estado)");
		$consulta->bindValue(':sector', $this->sector, PDO::PARAM_INT);
		$consulta->bindValue(':estado', $this->estado, PDO::PARAM_INT);
		$consulta->execute();		
		return $objetoAccesoDato->RetornarUltimoIdInsertado();
    }

    public function BajaDeMesa() {
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
		$consulta = $objetoAccesoDato->RetornarConsulta("DELETE from mesa WHERE id=:id");	
		$consulta->bindValue(':id', $this->id, PDO::PARAM_INT);		
		$consulta->execute();
		return $consulta->rowCount();
    }

    public function ModificacionDeMesa() {
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
		$consulta = $objetoAccesoDato->RetornarConsulta("UPDATE mesa set sector=:sector, estado=:estado WHERE id=:id");
        $consulta->bindValue(':id', $this->id, PDO::PARAM_INT);
        $consulta->bindValue(':sector',$this->sector, PDO::PARAM_INT);
		$consulta->bindValue(':estado', $this->estado, PDO::PARAM_INT);
		return $consulta->execute();
    }

    public static function TraerTodasLasMesas() {
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
	    $consulta = $objetoAccesoDato->RetornarConsulta("SELECT * from mesa");
	    $consulta->execute();			
        return $consulta->fetchAll(PDO::FETCH_CLASS, "Mesa");
    }

    public static function TraerMesaConId($id) {
		$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
		$consulta =$objetoAccesoDato->RetornarConsulta("SELECT * from mesa where id = $id");
		$consulta->execute();
		$mesa = $consulta->fetchObject('Mesa');
		return $mesa;
    }

    public function mostrarDatosDeLaMesa() {
	  	return "Mesa Numero: ".$this->id." - Estado: ".$this->estado." - Sector:  ".$this->sector;
    }
    public static function MasUsada()
    {
        try {
            $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();

            $consulta = $objetoAccesoDato->RetornarConsulta("SELECT f.codigoMesa, count(f.codigoMesa) as cantidad_usos FROM factura f 
                                                            GROUP BY(f.codigoMesa) HAVING count(f.codigoMesa) = 
                                                            (SELECT MAX(sel.cantidad_usos) FROM 
                                                            (SELECT count(f2.codigoMesa) as cantidad_usos FROM factura f2 GROUP BY(f2.codigoMesa)) sel);");

            $consulta->execute();

            $resultado = $consulta->fetchAll();
        } catch (Exception $e) {
            $resultado = $e->getMessage();
        }
        finally {
            return $resultado;
        }
    }
    
    public static function MenosUsada()
    {
        try {
            $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();

            $consulta = $objetoAccesoDato->RetornarConsulta("SELECT p.idmesa, count(p.idmesa) as cantidad_usos FROM pedido p
                                                            GROUP BY(p.idmesa) HAVING count(p.idmesa) = 
                                                            (SELECT MIN(subquery.cantidad_usos) FROM 
                                                            (SELECT count(p.idmesa) as cantidad_usos FROM pedido p GROUP BY(p.idmesa)) subquery);");

            $consulta->execute();

            $resultado = $consulta->fetchAll();
        } catch (Exception $e) {
            $mensaje = $e->getMessage();
            $resultado = array("Estado" => "ERROR", "Mensaje" => "$mensaje");
        }
        finally {
            return $resultado;
        }
    }
    
    public static function MasFacturacion()
    {
        try {
            $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();

            $consulta = $objetoAccesoDato->RetornarConsulta("SELECT p.idmesa, count(p.idmesa) as cantidad_usos FROM pedido p
                                                            GROUP BY(p.idmesa) HAVING count(p.idmesa) = 
                                                            (SELECT MAX(subquery.cantidad_usos) FROM 
                                                            (SELECT count(p.idmesa) as cantidad_usos FROM pedido p GROUP BY(p.idmesa)) subquery);");

            $consulta->execute();

            $resultado = $consulta->fetchAll();
        } catch (Exception $e) {
            $resultado = $e->getMessage();
        }
        finally {
            return $resultado;
        }
    }
    
    public static function MenosFacturacion()
    {
        try {
            $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();

            $consulta = $objetoAccesoDato->RetornarConsulta("SELECT f.idmesa, SUM(f.importe) as facturacion_total FROM factura f 
                                                            GROUP BY(f.idmesa) HAVING SUM(f.importe) = 
                                                            (SELECT MIN(sel.facturacion_total) FROM
                                                            (SELECT SUM(f2.importe) as facturacion_total FROM factura f2 GROUP BY(f2.idmesa)) sel)");

            $consulta->execute();

            $resultado = $consulta->fetchAll();
        } catch (Exception $e) {
            $resultado = $e->getMessage();
        }
        finally {
            return $resultado;
        }
    }
    
    public static function FacturaConMasImporte()
    {
        try {
            $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();

            $consulta = $objetoAccesoDato->RetornarConsulta("SELECT * FROM factura f WHERE f.importe = 
                                                            (SELECT MAX(f2.importe) as importe FROM factura f2 ) GROUP BY (f.idmesa);");

            $consulta->execute();

            $resultado = $consulta->fetchAll();
        } catch (Exception $e) {
            $mensaje = $e->getMessage();
            $resultado = array("Estado" => "ERROR", "Mensaje" => "$mensaje");
        }
        finally {
            return $resultado;
        }
    }
    
    public static function FacturaConMenosImporte()
    {
        try {
            $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();

            $consulta = $objetoAccesoDato->RetornarConsulta("SELECT * FROM factura f WHERE f.importe = 
                                                            (SELECT MIN(f2.importe) as importe FROM factura f2 ) GROUP BY (f.idmesa)");

            $consulta->execute();

            $resultado = $consulta->fetchAll();
        } catch (Exception $e) {
            $mensaje = $e->getMessage();
            $resultado = array("Estado" => "ERROR", "Mensaje" => "$mensaje");
        }
        finally {
            return $resultado;
        }
    }
    
    public static function MesaConMejorPuntuacion()
    {
        try {
            $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();

            $consulta = $objetoAccesoDato->RetornarConsulta("SELECT f.codigoMesa, AVG(f.puntuacion_mesa) as puntuacion_promedio FROM encuesta f 
                                                            GROUP BY(f.codigoMesa) HAVING AVG(f.puntuacion_mesa) = 
                                                            (SELECT MAX(sel.puntuacion_promedio) FROM
                                                            (SELECT AVG(f2.puntuacion_mesa) as puntuacion_promedio FROM encuesta f2 GROUP BY(f2.codigoMesa)) sel);");

            $consulta->execute();

            $resultado = $consulta->fetchAll();
        } catch (Exception $e) {
            $mensaje = $e->getMessage();
            $resultado = array("Estado" => "ERROR", "Mensaje" => "$mensaje");
        }
        finally {
            return $resultado;
        }
    }
    
    public static function MesaConPeorPuntuacion()
    {
        try {
            $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();

            $consulta = $objetoAccesoDato->RetornarConsulta("SELECT f.codigoMesa, AVG(f.puntuacion_mesa) as puntuacion_promedio FROM encuesta f 
                                                            GROUP BY(f.codigoMesa) HAVING AVG(f.puntuacion_mesa) = 
                                                            (SELECT MIN(sel.puntuacion_promedio) FROM
                                                            (SELECT AVG(f2.puntuacion_mesa) as puntuacion_promedio FROM encuesta f2 GROUP BY(f2.codigoMesa)) sel);");

            $consulta->execute();

            $resultado = $consulta->fetchAll();
        } catch (Exception $e) {
            $mensaje = $e->getMessage();
            $resultado = array("Estado" => "ERROR", "Mensaje" => "$mensaje");
        }
        finally {
            return $resultado;
        }
    }
    
    public static function FacturacionEntreFechas2($codigoMesa,$fecha1,$fecha2)
    {
        try {
            $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();

            $consulta = $objetoAccesoDato->RetornarConsulta("SELECT f.idmesa, f.fecha, f.importe FROM factura f 
                                                            WHERE f.codigoMesa = :idmesa AND f.fecha BETWEEN :fecha1 AND :fecha2;");

            $consulta->bindValue(':codigoMesa', $codigoMesa, PDO::PARAM_STR);
            $consulta->bindValue(':fecha1', $fecha1, PDO::PARAM_STR);
            $consulta->bindValue(':fecha2', $fecha2, PDO::PARAM_STR);
            $consulta->execute();

            $resultado = $consulta->fetchAll();
        } catch (Exception $e) {
            $mensaje = $e->getMessage();
            $resultado = array("Estado" => "ERROR", "Mensaje" => "$mensaje");
        }
        finally {
            return $resultado;
        }
    }
}

abstract class SectoresMesa {
    const BarraDeTragos = 1;//Bartender
    const BarraDeCervezas = 2;//Cervecero
    const Cocina = 3;//cocinero
    const CandyBar = 4;//Mozo
}


abstract class EstadosMesa {
    const Comiendo = 1;
    const Esperando = 2;
    const Pagando = 3;
    const Cerrada = 4;
}

?>