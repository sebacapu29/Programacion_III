<?php

class InfoEmpleado {

    public $id;
    public $fechaingreso;
    public $horarioingreso;
    public $idempleado;

    public function FichajeEmpleado() {
        $horaArgentina = new DateTime("now", new DateTimeZone('America/Argentina/Buenos_Aires'));
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
        $consulta = $objetoAccesoDato->RetornarConsulta("INSERT into infoEmpleado (horarioIngreso,fechaIngreso,idempleado)values(:horarioIngreso,:fechaIngreso,:idempleado)");
        $consulta->bindValue(':fechaIngreso', date("d-m-Y"), PDO::PARAM_STR);
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
        // return $consulta->fetchAll(PDO::FETCH_CLASS, "InfoEmpleado");
        return $consulta->idempleado;
    }
}

?>