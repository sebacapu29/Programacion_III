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
        $consulta = $objetoAccesoDato->RetornarConsulta("INSERT into factura (fecha,idresponsable,idpedido,idmesa,importe)values(:fecha,:idresponsable,:idpedido,:idmesa,:importe)");
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
        $consulta = $objetoAccesoDato->RetornarConsulta("DELETE from factura WHERE id=:id");	
        $consulta->bindValue(':id', $this->id, PDO::PARAM_INT);		
        $consulta->execute();
        return $consulta->rowCount();
    }
    public static function TraerFactura($id) {
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
        $consulta = $objetoAccesoDato->RetornarConsulta("SELECT * from factura WHERE id=:id");	
        $consulta->bindValue(':id', $id, PDO::PARAM_INT);		
        $consulta->execute();
        return $consulta->fetchObject("Factura");
    }

    public function ModificacionDeFactura() {
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
        $consulta = $objetoAccesoDato->RetornarConsulta("UPDATE factura set fecha=:fecha, idresponsable=:idresponsable, idmesa=:idmesa, idpedido=:idpedido, importe=:importe WHERE id=:id");
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
        if($mes < 10){
            $mes = '0' . $mes;
        }
        $fechaInicial = '2019-'.$mes.'-'.'01';
        $fechaFinal = '2019-'.$mes.'-'.$cantDias;
  
        
        $consulta = $objetoAccesoDato->RetornarConsulta("SELECT ROUND(AVG(importe),3) as promedioMensual FROM factura f
                                                         WHERE f.fecha BETWEEN  :fecha1 AND  :fecha2");
        $consulta->bindValue(':fecha1', $fechaInicial, PDO::PARAM_STR);
        $consulta->bindValue(':fecha2', $fechaFinal, PDO::PARAM_STR);
        $consulta->execute();
        $retornoDeConsulta = $consulta->fetchAll(PDO::FETCH_ASSOC);
        if($retornoDeConsulta[0][0]==NULL){
            return "No se encontro registro de facturacion en el mes ingresado";
        }
        return $consulta->fetchAll(PDO::FETCH_ASSOC);
    }
}

?>