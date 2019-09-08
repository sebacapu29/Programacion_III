<?php

class Factura {

    public $id;
    public $fecha;
    public $idresponsable;
    public $idpedido;
    public $idmesa;
    public $importe;

    public function AltaDeFactura() {
        $this->fecha = date("Y-m-d");
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
        $consulta = $objetoAccesoDato->RetornarConsulta("INSERT into facturas (fecha,idresponsable,idpedido,idmesa,importe)values(:fecha,:idresponsable,:idpedido,:idmesa,:importe)");
        $consulta->bindValue(':fecha', $this->fecha, PDO::PARAM_STR);
        $consulta->bindValue(':idresponsable', $this->idresponsable, PDO::PARAM_INT);
        $consulta->bindValue(':idpedido', $this->idpedido, PDO::PARAM_INT);
        $consulta->bindValue(':idmesa', $this->idmesa, PDO::PARAM_INT);
        $consulta->bindValue(':importe', $this->importe, PDO::PARAM_INT);
        $consulta->execute();
        return $objetoAccesoDato->RetornarUltimoIdInsertado();
    }

    public function BajaDeFactura() {
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
        $consulta = $objetoAccesoDato->RetornarConsulta("DELETE from facturas WHERE id=:id");	
        $consulta->bindValue(':id', $this->id, PDO::PARAM_INT);		
        $consulta->execute();
        return $consulta->rowCount();
    }

    public function ModificacionDeFactura() {
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
        $consulta = $objetoAccesoDato->RetornarConsulta("UPDATE facturas set fecha=:fecha, idresponsable=:idresponsable, idmesa=:idmesa, idpedido=:idpedido, importe=:importe WHERE id=:id");
        $consulta->bindValue(':id', $this->id, PDO::PARAM_INT);
        $consulta->bindValue(':fecha', $this->fecha, PDO::PARAM_STR);
        $consulta->bindValue(':idresponsable', $this->idresponsable, PDO::PARAM_INT);
        $consulta->bindValue(':idmesa', $this->idmesa, PDO::PARAM_INT);
        $consulta->bindValue(':idpedido', $this->idpedido, PDO::PARAM_INT);
        $consulta->bindValue(':importe', $this->importe, PDO::PARAM_INT);
        return $consulta->execute();
    }
    public function PromedioMensualImporte($mes) {
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
        $consulta = $objetoAccesoDato->RetornarConsulta("SELECT AVG(importe) as promedioMensual FROM factura f
                                                         WHERE f.fecha BETWEEN  :fecha1 AND  :fecha2");
        $consulta->bindValue(':fecha1', $fechaInicial, PDO::PARAM_STR);
        $consulta->bindValue(':fecha2', $fechaFinal, PDO::PARAM_STR);
        return $consulta->execute();
    }
}

?>