<?php

class Comentario {

    public $id;
    public $puntaje;
    public $descripcion;
    public $idMesa;
    public $fecha;
    
    public function AltaDeComentario() {
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
        $consulta = $objetoAccesoDato->RetornarConsulta("INSERT into comentarios (puntaje,descripcion,idmesa,fecha)values(:puntaje,:descripcion,:idmesa,:fecha)");
        $consulta->bindValue(':puntaje', $this->puntaje, PDO::PARAM_INT);
        $consulta->bindValue(':descripcion', $this->descripcion, PDO::PARAM_STR);
        $consulta->bindValue(':idmesa', $this->idMesa, PDO::PARAM_INT);
        $consulta->bindValue(':fecha',date("Y-m-d"),PDO::PARAM_STR);
        $consulta->execute();		
        return $objetoAccesoDato->RetornarUltimoIdInsertado();
    }

    public function BajaDeComentario() {
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
        $consulta = $objetoAccesoDato->RetornarConsulta("DELETE from comentarios WHERE id=:id");	
        $consulta->bindValue(':id', $this->id, PDO::PARAM_INT);		
        $consulta->execute();
        return $consulta->rowCount();
    }

    public function ModificacionDeComentario() {
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
        $consulta = $objetoAccesoDato->RetornarConsulta("UPDATE comentarios set puntaje=:puntaje, descripcion=:descripcion, idmesa=:idmesa WHERE id=:id");
        $consulta->bindValue(':id', $this->id, PDO::PARAM_INT);
        $consulta->bindValue(':puntaje', $this->puntaje, PDO::PARAM_INT);
        $consulta->bindValue(':descripcion', $this->descripcion, PDO::PARAM_INT);
        $consulta->bindValue(':idmesa', $this->idMesa, PDO::PARAM_INT);
        return $consulta->execute();
    }

    public static function TraerTodosLosComentarios() {
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
	    $consulta = $objetoAccesoDato->RetornarConsulta("SELECT * from comentarios");
	    $consulta->execute();			
        return $consulta->fetchAll(PDO::FETCH_CLASS, "Comentario");
    }

    public static function TraerComentarioConId($id) {
		$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
		$consulta =$objetoAccesoDato->RetornarConsulta("SELECT * from comentarios where id = $id");
		$consulta->execute();
		$comentario = $consulta->fetchObject('Comentario');
		return $comentario;
    }
    public static function MesaConMejorPuntuacion()
    {
        try {
            $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();

            $consulta = $objetoAccesoDato->RetornarConsulta("SELECT c.idmesa, AVG(c.puntaje) as puntuacion_promedio,c.descripcion FROM comentario c 
                                                             GROUP BY(c.idmesa) HAVING AVG(c.puntaje) = 
                                                             (SELECT MAX(subquery.puntuacion_promedio) FROM 
                                                             (SELECT AVG(c2.puntaje) as puntuacion_promedio FROM comentario c2 GROUP BY(c2.idmesa)) subquery)");

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

            $consulta = $objetoAccesoDato->RetornarConsulta("SELECT c.idmesa, AVG(c.puntaje)as puntuacion_promedio ,c.descripcion 
                                                            FROM comentario c GROUP BY(c.idmesa) HAVING AVG(c.puntaje) = 
                                                            (SELECT MIN(subquery.puntuacion_promedio) FROM 
                                                            (SELECT AVG(c2.puntaje) as puntuacion_promedio FROM comentario c2 GROUP BY(c2.idmesa)) subquery)");
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

?>