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
    
    public static function FacturaConMayorImporte()
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
    
    public static function FacturaConMenorImporte()
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
    
    public static function MejorPuntuacion()
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
    
    public static function PeorPuntuacion()
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
    
    public static function FacturacionEntreFechas2($fecha1,$fecha2)
    {
        try {
            $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();

            $consulta = $objetoAccesoDato->RetornarConsulta("SELECT f.fecha,f.idresponsable,f.idpedido,f.idmesa,f.importe FROM factura f 
                                                            WHERE f.fecha BETWEEN  :fecha1 AND  :fecha2;");

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
    public static function PuntuacionEntreDosFechas($fecha1,$fecha2)
    {
        try {
            $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();

            $consulta = $objetoAccesoDato->RetornarConsulta("SELECT * FROM comentario c 
                                                            WHERE f.fecha BETWEEN  :fecha1 AND  :fecha2;");

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
    public function PromedioMensualMesas($mes) {
        $cantDias=0;

        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
        switch ($mes) {
            case 1:
            case 3:
            case 5:
            case 7:
            case 8:
            case 10:
            case 12:
                $cantDias = 31;
                break;
            case 4:
            case 6:
            case 9:
            case 11:
                $cantDias = 30;
                break;
            case 2:
                if (((2019 % 4 == 0) && 
                     !(2019 % 100 == 0))
                     || (2019 % 400 == 0))
                     $cantDias = 29;
                else
                     $cantDias = 28;
                break;
            default:
                return "Mes no válido";
                break;
        }
        $fechaInicial = '2019-'.$mes.'-'.'01';
        $fechaFinal = '2019-'.$mes.'-'.$cantDias;
        $consulta = $objetoAccesoDato->RetornarConsulta("SELECT AVG(idmesa) as promedioMensual FROM factura f
                                                         WHERE f.fecha BETWEEN  :fecha1 AND  :fecha2");
        $consulta->bindValue(':fecha1', $fechaInicial, PDO::PARAM_STR);
        $consulta->bindValue(':fecha2', $fechaFinal, PDO::PARAM_STR);
        return $consulta->execute();
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