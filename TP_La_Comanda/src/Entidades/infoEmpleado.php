<?php

class InfoEmpleado {

    public $id;
    public $fechaingreso;
    public $horarioingreso;
    public $idempleado;

    public function FichajeEmpleado() {
        $horaArgentina = new DateTime("now", new DateTimeZone('America/Argentina/Buenos_Aires'));
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
        $consulta = $objetoAccesoDato->RetornarConsulta("INSERT into infoempleado (horarioingreso,fechaingreso,idempleado)values(:horarioIngreso,:fechaIngreso,:idempleado)");
        $consulta->bindValue(':fechaIngreso', date("Y-m-d"), PDO::PARAM_STR);
        $consulta->bindValue(':horarioIngreso',$horaArgentina->format('H:i:s'),PDO::PARAM_STR);
        $consulta->bindValue(':idempleado', $this->idempleado, PDO::PARAM_INT);
        $consulta->execute();
        return $objetoAccesoDato->RetornarUltimoIdInsertado();
    }
    public static function ObtenerDiasYHorarios(){
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
	    $consulta = $objetoAccesoDato->RetornarConsulta("SELECT * from infoempleado");
	    $consulta->execute();			
        return $consulta->fetchAll(PDO::FETCH_CLASS, "InfoEmpleado");
    }
    public function BajaDeHorario() {
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
        $consulta = $objetoAccesoDato->RetornarConsulta("DELETE from horarios WHERE id=:id");	
        $consulta->bindValue(':id', $this->id, PDO::PARAM_INT);		
        $consulta->execute();
        return $consulta->rowCount();
    }

    public function ModificacionDeHorario() {
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
        $consulta = $objetoAccesoDato->RetornarConsulta("UPDATE horarios set fecha=:fecha, idempleado=:idempleado WHERE id=:id");
        $consulta->bindValue(':id', $this->id, PDO::PARAM_INT);
        $consulta->bindValue(':fecha', $this->fecha, PDO::PARAM_STR);
        $consulta->bindValue(':idempleado', $this->idempleado, PDO::PARAM_STR);
        return $consulta->execute();
    }
    public function RegistroDeOperacion($operacion,$idempleado) {
        $horaArgentina = new DateTime("now", new DateTimeZone('America/Argentina/Buenos_Aires'));
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
        $consulta = $objetoAccesoDato->RetornarConsulta("INSERT into operacionEmpleado (operacion,idempleado,sector,horaoperacion)values(:operacion,:idempleado,:sector,:horaoperacion)");
        $consulta->bindValue(':operacion', $operacion, PDO::PARAM_STR);
        $consulta->bindValue(':idEmpleado', $this->idempleado, PDO::PARAM_INT);
        $consulta->bindValue(':sector', null, PDO::PARAM_NULL);
        $consulta->bindValue(':horaOperacion',$horaArgentina->format('H:i:s'),PDO::PARAM_STR);
        $consulta->execute();
        return $objetoAccesoDato->RetornarUltimoIdInsertado();
    }
    public function ActualizarRegistroDeOperacion() {

        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
        $consulta = $objetoAccesoDato->RetornarConsulta("UPDATE operacionEmpleado SET sector = :sector
                                                        WHERE idempleado=:idempleado");

        $consulta->bindValue(':idEmpleado',$this->idempleado, PDO::PARAM_INT);
        $consulta->bindValue(':sector', $this->sector, PDO::PARAM_INT);
        $consulta->execute();
        return $objetoAccesoDato->RetornarUltimoIdInsertado();
    }
    public static function ObtenerUltimaInfoIngresada(){
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
	    $consulta = $objetoAccesoDato->RetornarConsulta("SELECT MAX(idEmpleado) from infoempleado");
	    $consulta->execute();			
        return $consulta->idempleado;
    }
    public function TraerOperacionesPorSector($request, $response, $args) {
        
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
	    $consulta = $objetoAccesoDato->RetornarConsulta("SELECT sector,count(*) as cantidad_operaciones 
                                                         FROM operacionempleado GROUP BY(sector)");
	    $consulta->execute();			
        return $consulta->idempleado;

        return $response->withJson($objDelaRespuesta, 200);
    }
    public function TraerOperacionesPorSectorPorEmpleados($request, $response, $args) {
        
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
	    $consulta = $objetoAccesoDato->RetornarConsulta("SELECT subquery.sector, subquery.cantidad_operacion,subquery.nombre,subquery.apellido FROM 
                                                        (SELECT op.sector,count(*) as cantidad_operacion,emp.nombre,emp.apellido FROM operacionempleado op,empleado emp
                                                        WHERE op.idempleado = emp.id
                                                        GROUP BY (op.sector)) subquery;");
	    $consulta->execute();			
        return $consulta->idempleado;

        return $response->withJson($objDelaRespuesta, 200);
    }
    public function TraerOperacionesEmpleados($request, $response, $args) {
        
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
	    $consulta = $objetoAccesoDato->RetornarConsulta("SELECT subquery.cantidad_operacion,subquery.nombre,subquery.apellido FROM 
                                                        (SELECT op.sector,count(*) as cantidad_operacion,emp.nombre,emp.apellido FROM operacionempleado op,empleado emp
                                                        WHERE op.idempleado = emp.id
                                                        GROUP BY (emp.id)) subquery;");
	    $consulta->execute();			
        return $consulta->idempleado;
    }
}

?>