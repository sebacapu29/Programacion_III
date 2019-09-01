<?php
include_once '../src/AccesoDatos/AccesoDatos.php';

class Menu{
    public $id;
    public $precio;
    public $nombre;
    public $sector;

    public static function Mostrar()
    {
        try {
            $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();

            $consulta = $objetoAccesoDato->RetornarConsulta("SELECT * FROM menu;");

            $consulta->execute();

            $resultado = $consulta->fetchAll(PDO::FETCH_CLASS, "Menu");
        } catch (Exception $e) {
            $resultado = $e->getMessage();
        }
        finally {
            return $resultado;
        }
    }
    public static function MostrarPorSector($sector)
    {
        try {
            $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();

            $consulta = $objetoAccesoDato->RetornarConsulta("SELECT * FROM menu WHERE sector = :sector;");

            $consulta->execute();
            $consulta->bindValue(":sector",$sector,PDO::PARAM_INT);
            $resultado = $consulta->fetchAll(PDO::FETCH_CLASS, "Menu");
        } catch (Exception $e) {
            $resultado = $e->getMessage();
        }
        finally {
            return $resultado;
        }
    }
}

?>