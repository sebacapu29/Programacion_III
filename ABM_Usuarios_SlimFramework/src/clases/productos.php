<?php
include_once '../src/clases/AccesoDatos.php';

class Producto{
    public $id;
    public $descripcion;
    public $tipo;
    public $fechaDeVencimiento;
    public $precio;
    public $rutaDeFoto;
    
    public static function TraerTodoLosProductos()
	{
			$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
			$consulta =$objetoAccesoDato->RetornarConsulta("select * from productos");
			$consulta->execute();			
			return $consulta->fetchAll(PDO::FETCH_CLASS, "producto");		
    }
    public static function TraerProductoPorDescripcion($request,$response,$args)
	{
            $descripcion = $request->getParam("descripcion");  
			$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
            $consulta =$objetoAccesoDato->RetornarConsulta("select * from productos
                                                            where descripcion = :descripcion");
            $consulta->bindValue(':descripcion',$descripcion, PDO::PARAM_STR);
			$consulta->execute();			
			return $consulta->fetchAll(PDO::FETCH_CLASS, "producto");		
    }
  	public function BorrarProducto($request,$response,$args)
    {
            $arrayParametros = $request->getParsedBody();
            $id = $arrayParametros["id"];

              $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
             $consulta = $objetoAccesoDato->RetornarConsulta("
                 delete 
                 from productos 				
                 WHERE id=:id");	
                 $consulta->bindValue(':id',$id, PDO::PARAM_INT);		
                 $consulta->execute();
                 return $consulta->rowCount();
    }
       public function Modificar()
      {
             $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
             $consulta =$objetoAccesoDato->RetornarConsulta("
                 update productos 
                 set descripcion=:descripcion,
                 tipo=:tipo,
                 fechaDeVencimiento= :fechaDeVencimiento,
                 precio = :precio,
                 rutaDeFoto = :rutaDefoto
                 WHERE id=:id");
             $consulta->bindValue(':descripcion',$this->descripcion, PDO::PARAM_STR);
             $consulta->bindValue(':tipo',$this->tipo, PDO::PARAM_STR);
             $consulta->bindValue(':fechaDeVencimiento', $this->fechaDeVencimiento, PDO::PARAM_STR);
             $consulta->bindValue(':precio', $this->precio, PDO::PARAM_INT);
             $consulta->bindValue(':rutaDeFoto', $this->rutaDeFoto, PDO::PARAM_STR);
             
             return $consulta->execute();
      }
 
      public function InsertarElproductos()
      {
                 $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
                 $consulta =$objetoAccesoDato->RetornarConsulta("INSERT into productos (productos,clave,sexo,perfil)values
                                                                (:productos,:clave,:sexo,:perfil)");
                 $consulta->bindValue(':productos', $this->productos, PDO::PARAM_STR);
                 $consulta->bindValue(':clave', $this->clave, PDO::PARAM_STR);
                 $consulta->bindValue(':sexo', $this->sexo, PDO::PARAM_STR);
                 $consulta->bindValue(':perfil', $this->perfil, PDO::PARAM_STR);
                 $consulta->execute();		
                 return $objetoAccesoDato->RetornarUltimoInsertado();
      }
      public function Guardarproductos()
      {
          if($this->sexo>0)
              {
                  $this->ModificarproductosParametros();
              }else {
                  $this->InsertarElproductosParametros();
              }
      }
 
     public static function TraerproductosPorId($id) 
     {
             $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
             $consulta =$objetoAccesoDato->RetornarConsulta("select * from productos where id = :id");
             $consulta->bindValue(':id', $id, PDO::PARAM_INT);
             $consulta->execute();
             $productosBuscado= $consulta->fetchObject('productos');
             return $productosBuscado;				         
     }
     public static function TraerUnproductosclaveArray($productos,$clave) 
     {
             $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
             $consulta =$objetoAccesoDato->RetornarConsulta("select  sexo,productos,clave from productos  WHERE productos=:productos AND clave=:clave");
             $consulta->execute(array(':productos'=> $productos,':clave'=> $clave));
             $consulta->execute();
             $cdBuscado= $consulta->fetchObject('productos');
               return $cdBuscado;				     
     }
}

?>