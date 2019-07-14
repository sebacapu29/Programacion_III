<?php
class Empleado {
    
    public $id;
    public $tipo;
    public $estado;

    public function AltaDeEmpleado() {
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
        $consulta = $objetoAccesoDato->RetornarConsulta("INSERT into empleado (tipo,estado)values(:tipo,:estado)");
        $consulta->bindValue(':tipo', $this->tipo, PDO::PARAM_INT);
        $consulta->bindValue(':estado', $this->estado, PDO::PARAM_INT);
        $consulta->execute();
        return $objetoAccesoDato->RetornarUltimoIdInsertado();
    }

    public function BajaDeEmpleado() {
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
        $consulta = $objetoAccesoDato->RetornarConsulta("DELETE from empleado WHERE id=:id");	
        $consulta->bindValue(':id', $this->id, PDO::PARAM_INT);		
        $consulta->execute();
        return $consulta->rowCount();
    }

    public function ModificacionDeEmpleado() {
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
        $consulta = $objetoAccesoDato->RetornarConsulta("UPDATE empleado set tipo=:tipo, estado=:estado WHERE id=:id");
        $consulta->bindValue(':id', $this->id, PDO::PARAM_INT);
        $consulta->bindValue(':tipo', $this->tipo, PDO::PARAM_INT);
        $consulta->bindValue(':estado', $this->estado, PDO::PARAM_INT);
        return $consulta->execute();
    }

    public static function TraerTodosLosEmpleados() {
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
	    $consulta = $objetoAccesoDato->RetornarConsulta("SELECT * from empleado");
	    $consulta->execute();			
        return $consulta->fetchAll(PDO::FETCH_CLASS, "empleado");
    }

    public static function TraerEmpleadoConId($id) {
		$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
		$consulta =$objetoAccesoDato->RetornarConsulta("SELECT * from empleado where id = $id");
		$consulta->execute();
		$empleado = $consulta->fetchObject('Empleado');
		return $empleado;
    }
}
abstract class TipoDeEmpleado {
    const Socio = 1;
    const Mozo = 2;
    const Cocinero = 3;
    const Cervecero = 4;
    const Bartender = 5;
}

abstract class EstadoEmpleado {
    const Activo = 0;
    const Inactivo = 1;
    const Suspendido = 2;
}

?>