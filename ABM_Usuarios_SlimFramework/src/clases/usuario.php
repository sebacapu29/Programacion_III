<?php
include_once '../src/clases/AccesoDatos.php';

class usuario{
    public $id;
    public $sexo;
    public $usuario;
    public $clave;
    public $perfil;
    
    public static function TraerTodoLosusuarios()
	{
			$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
			$consulta =$objetoAccesoDato->RetornarConsulta("select * from usuario");
			$consulta->execute();			
			return $consulta->fetchAll(PDO::FETCH_CLASS, "usuario");		
    }
    
  	public function Borrar($id)
    {
              $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
             $consulta = $objetoAccesoDato->RetornarConsulta("
                 delete 
                 from usuario 				
                 WHERE id=:id");	
                 $consulta->bindValue(':id',$id, PDO::PARAM_STR);		
                 $consulta->execute();
                 return $consulta->rowCount();
    }
 
     public static function BorrarusuarioPorNombre($usuario)
      {
             $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
             $consulta =$objetoAccesoDato->RetornarConsulta("
                 delete 
                 from usuario 				
                 WHERE usuario=:usuario");	
                 $consulta->bindValue(':usuario',$usuario, PDO::PARAM_STR);		
                 $consulta->execute();
                 return $consulta->rowCount();
 
      }
       public function Modificar()
      {
             $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
             $consulta =$objetoAccesoDato->RetornarConsulta("
                 update usuario 
                 set usuario=:usuario,
                 clave=:clave,
                 sexo = :sexo
                 WHERE id=:id");
             $consulta->bindValue(':sexo',$this->sexo, PDO::PARAM_STR);
             $consulta->bindValue(':usuario',$this->usuario, PDO::PARAM_STR);
             $consulta->bindValue(':clave', $this->clave, PDO::PARAM_STR);
             $consulta->bindValue(':perfil', $this->perfil, PDO::PARAM_STR);
             $consulta->bindValue(':id', $this->id, PDO::PARAM_STR);
             return $consulta->execute();
      }
 
      public function InsertarElUsuario()
      {
                 $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
                 $consulta =$objetoAccesoDato->RetornarConsulta("INSERT into usuario (usuario,clave,sexo,perfil)values
                                                                (:usuario,:clave,:sexo,:perfil)");
                 $consulta->bindValue(':usuario', $this->usuario, PDO::PARAM_STR);
                 $consulta->bindValue(':clave', $this->clave, PDO::PARAM_STR);
                 $consulta->bindValue(':sexo', $this->sexo, PDO::PARAM_STR);
                 $consulta->bindValue(':perfil', $this->perfil, PDO::PARAM_STR);
                 $consulta->execute();		
                 return $objetoAccesoDato->RetornarUltimoInsertado();
      }
      public function Guardarusuario()
      {
          if($this->sexo>0)
              {
                  $this->ModificarusuarioParametros();
              }else {
                  $this->InsertarElusuarioParametros();
              }
      }
 
     public static function TraerUnusuario($id) 
     {
             $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
             $consulta =$objetoAccesoDato->RetornarConsulta("select sexo,usuario,clave from usuario where sexo = $id");
             $consulta->execute();
             $usuarioBuscado= $consulta->fetchObject('usuario');
             return $usuarioBuscado;				         
     }
     public static function TraerUnusuarioclaveArray($usuario,$clave) 
     {
             $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
             $consulta =$objetoAccesoDato->RetornarConsulta("select  sexo,usuario,clave from usuario  WHERE usuario=:usuario AND clave=:clave");
             $consulta->execute(array(':usuario'=> $usuario,':clave'=> $clave));
             $consulta->execute();
             $cdBuscado= $consulta->fetchObject('usuario');
               return $cdBuscado;				     
     }
}

?>