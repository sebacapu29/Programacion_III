<?php
class Empleado {
    
    public $id;
    public $tipo;
    public $estado;
    public $usuario;
    public $clave;
    
    public function AltaDeEmpleado() {
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
        $consulta = $objetoAccesoDato->RetornarConsulta("INSERT into empleado (tipo,estado,usuario,clave)
                                                                                values(:tipo,:estado,:usuario,:clave)");
        $consulta->bindValue(':tipo', $this->tipo, PDO::PARAM_INT);
        $consulta->bindValue(':estado', $this->estado, PDO::PARAM_INT);
        $consulta->bindValue(':usuario', $this->usuario, PDO::PARAM_STR);
        $consulta->bindValue(':clave', $this->clave, PDO::PARAM_STR);
        $consulta->execute();
        return $objetoAccesoDato->RetornarUltimoIdInsertado();
    }

    public function CambiarEstadoEmpleado(){
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
        $consulta = $objetoAccesoDato->RetornarConsulta("UPDATE empleado SET
                                                        estado = :nuevoEstado WHERE id=:id");	
        $consulta->bindValue(':id', $this->id, PDO::PARAM_INT);		
        $consulta->bindValue(':nuevoEstado', $this->estado, PDO::PARAM_INT);		
        $consulta->execute();
        return $consulta->rowCount();
    }
    public function BorradoDeEmpleado() {
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
        $consulta = $objetoAccesoDato->RetornarConsulta("DELETE FROM empleado WHERE id = :id");	
        $consulta->bindValue(':id', $this->id, PDO::PARAM_INT);		
        $consulta->execute();
        return $consulta->rowCount();
    }

    public function ModificacionDeEmpleado() {
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
        $consulta = $objetoAccesoDato->RetornarConsulta("UPDATE empleado set tipo=:tipo,
                                                        estado= :estado,
                                                        usuario= :usuario,
                                                        clave = :clave 
                                                        WHERE id=:id");
        $consulta->bindValue(':id', $this->id, PDO::PARAM_INT);
        $consulta->bindValue(':tipo', $this->tipo, PDO::PARAM_INT);
        $consulta->bindValue(':estado', $this->estado, PDO::PARAM_INT);
        $consulta->bindValue(':usuario', $this->usuario, PDO::PARAM_STR);
        $consulta->bindValue(':clave', $this->clave, PDO::PARAM_STR);
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
    public static function ObtenerEmpleadoUsuario($usuario,$clave) {
		$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
		$consulta =$objetoAccesoDato->RetornarConsulta("SELECT * from empleado 
                                                        where usuario = :usuario and clave = :clave");
        $consulta->bindValue(':usuario', $usuario, PDO::PARAM_STR);
        $consulta->bindValue(':clave', $clave, PDO::PARAM_STR);
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
    const Adim=6;
}

abstract class EstadoEmpleado {
    const Activo = 0;
    const Inactivo = 1;
    const Suspendido = 2;
}

?>