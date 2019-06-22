<?php
include_once '../src/clases/AccesoDatos.php';

class usuario{
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
    
  	public function Borrarusuario()
    {
              $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
             $consulta = $objetoAccesoDato->RetornarConsulta("
                 delete 
                 from usuario 				
                 WHERE sexo=:sexo");	
                 $consulta->bindValue(':sexo',trim($this->clave), PDO::PARAM_STR);		
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
     public function Modificarusuario()
      {
             $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
             $consulta =$objetoAccesoDato->RetornarConsulta("
                 update usuario 
                 set usuario='$this->usuario',
                 clave='$this->clave',                 
                 WHERE sexo='$this->sexo'");
             return $consulta->execute();
      }
     
   
      public function Insertarusuario()
      {
                 $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
                 $consulta =$objetoAccesoDato->RetornarConsulta("INSERT into usuario (usuario,clave)values('$this->usuario','$this->clave')");
                 $consulta->execute();
                 return $objetoAccesoDato->RetornarUltimosexoInsertado();          
      }
 
       public function ModificarusuarioParametros()
      {
             $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
             $consulta =$objetoAccesoDato->RetornarConsulta("
                 update usuario 
                 set usuario=:usuario,
                 clave=:clave
                 WHERE sexo=:sexo");
             $consulta->bindValue(':sexo',$this->sexo, PDO::PARAM_INT);
             $consulta->bindValue(':usuario',$this->usuario, PDO::PARAM_STR);
             $consulta->bindValue(':clave', $this->clave, PDO::PARAM_STR);
             return $consulta->execute();
      }
 
      public function InsertarElUsuarioParametros()
      {
                 $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
                 $consulta =$objetoAccesoDato->RetornarConsulta("INSERT into usuario (usuario,clave,sexo,perfil)values(:usuario,:clave,:sexo,:perfil)");
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
       public static function TraerTodoLosusuarios2()
     {
             $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
             $consulta =$objetoAccesoDato->RetornarConsulta("select * from usuario");
             $consulta->execute();			
             return $consulta->fetchAll(PDO::FETCH_CLASS, "usuario");		
     }
 
     public static function TraerUnusuario($sexo) 
     {
             $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
             $consulta =$objetoAccesoDato->RetornarConsulta("select sexo,usuario,clave from usuario where sexo = $sexo");
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