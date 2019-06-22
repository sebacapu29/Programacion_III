<?php
include_once 'AccesoDatos.php';
include_once 'AutentificadorJWT.php';

class Compra{

    public $articulo;
    public $precio;
    public $fecha;
    public $usuario;

    public function RealizarCompra($foto)
    {
        $objRespuestaDB = new stdClass();
            $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
            $consulta =$objetoAccesoDato->RetornarConsulta("INSERT into compra (articulo,precio,usuario,fecha)values(:articulo,:precio,:usuario,:fecha)");
            $consulta->bindValue(':articulo', $this->articulo, PDO::PARAM_STR);
            $consulta->bindValue(':precio', $this->precio, PDO::PARAM_STR);
            $consulta->bindValue(':usuario',$this->usuario, PDO::PARAM_STR);
            $consulta->bindValue(':fecha', $this->fecha, PDO::PARAM_STR);
            $consulta->execute();	
            $id =$objetoAccesoDato->RetornarUltimoInsertado();
            $articulo = $this->articulo;

            GuardarFotos($foto,$id,$articulo);

            if($objetoAccesoDato->RetornarUltimoInsertado()>=0){
                $objRespuestaDB->descripcion = "Compra Realizada Con Exito";
                $objRespuestaDB->inserto = true;
                return $objRespuestaDB;
            }	
            else{
                $objRespuestaDB->$descripcion = "No se pudo realizar Compra";
                $objRespuestaDB->$inserto = false;
                return $objRespuestaDB;
            }
    }
    public static function RetornarCompra($perfil,$usuario){

        $query ="";

        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 

        if($perfil=='admin'){
            $query="SELECT * FROM compra";
        }
        else if($perfil=='usuario'){
            $query="SELECT * FROM compra
                    WHERE usuario= :usuario";    
        }
        $consulta =$objetoAccesoDato->RetornarConsulta($query);
        
        if($perfil=='usuario')
            $consulta->bindValue(':usuario', $usuario, PDO::PARAM_STR);

        $consulta->execute();
        return $consulta->fetchAll(PDO::FETCH_CLASS, "compra");
    }
}
function GuardarFotos($foto,$id,$articulo)
{
    $fileFoto = $foto;
    $name = $fileFoto['name'];
    $temp_name = $fileFoto['tmp_name'];
    $fotoNueva = false;
    $pathImg = LeerConfigJson();
    $auxnombre = explode(".",$name);
    $nombreFoto = $auxnombre[0] . $id . $articulo;

    $path = $pathImg . '/'. $nombreFoto ."." .$auxnombre[1] ;
    move_uploaded_file($temp_name,$path);
}
function LeerConfigJson(){
    $data = file_get_contents("../src/archivos/config.json");
    $config = json_decode($data, true);
    
    return $config[0]["pathImg"];
}
?>