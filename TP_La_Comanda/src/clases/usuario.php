<?php

class usuario{
    public $ID;
    public $USUARIO;
    public $PASSWORD;

    public static function TraerTodoLosUsuarios()
	{
			$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
			$consulta =$objetoAccesoDato->RetornarConsulta("select * from usuario");
			$consulta->execute();			
			return $consulta->fetchAll(PDO::FETCH_CLASS, "usuario");		
    }
    
  	public function BorrarUsuario()
      {
              $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
             $consulta =$objetoAccesoDato->RetornarConsulta("
                 delete 
                 from usuario 				
                 WHERE ID=:id");	
                 $consulta->bindValue(':id',trim($this->ID), PDO::PARAM_STR);		
                 $consulta->execute();
                 return $consulta->rowCount();
      }
 
     public static function BorrarUsuarioPorNombre($usuario)
      {
             $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
             $consulta =$objetoAccesoDato->RetornarConsulta("
                 delete 
                 from USUARIO 				
                 WHERE USUARIO=:USUARIO");	
                 $consulta->bindValue(':USUARIO',$USUARIO, PDO::PARAM_STR);		
                 $consulta->execute();
                 return $consulta->rowCount();
 
      }
     public function ModificarUsuario()
      {
             $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
             $consulta =$objetoAccesoDato->RetornarConsulta("
                 update USUARIO 
                 set USUARIO='$this->USUARIO',
                 PASSWORD='$this->PASSWORD',                 
                 WHERE id='$this->ID'");
             return $consulta->execute();
 
      }
     
   
      public function InsertarUsuario()
      {
                 $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
                 $consulta =$objetoAccesoDato->RetornarConsulta("INSERT into cds (usuario,password)values('$this->USUARIO','$this->PASSWORD')");
                 $consulta->execute();
                 return $objetoAccesoDato->RetornarUltimoIdInsertado();          
      }
 
       public function ModificarUsuarioParametros()
      {
             $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
             $consulta =$objetoAccesoDato->RetornarConsulta("
                 update usuario 
                 set usuario=:usuario,
                 password=:password
                 WHERE id=:id");
             $consulta->bindValue(':id',$this->ID, PDO::PARAM_INT);
             $consulta->bindValue(':usuario',$this->USUARIO, PDO::PARAM_STR);
             $consulta->bindValue(':password', $this->PASSWORD, PDO::PARAM_STR);
             return $consulta->execute();
      }
 
      public function InsertarElUsuarioParametros()
      {
                 $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
                 $consulta =$objetoAccesoDato->RetornarConsulta("INSERT into usuario (usuario,password)values(:usuario,:password)");
                 $consulta->bindValue(':usuario', $this->USUARIO, PDO::PARAM_STR);
                 $consulta->bindValue(':password', $this->PASSWORD, PDO::PARAM_STR);
                 $consulta->execute();		
                 return $objetoAccesoDato->RetornarUltimoIdInsertado();
      }
      public function GuardarUsuario()
      {
          if($this->id>0)
              {
                  $this->ModificarUsuarioParametros();
              }else {
                  $this->InsertarElUsuarioParametros();
              }
      }
       public static function TraerTodoLosUsuarios2()
     {
             $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
             $consulta =$objetoAccesoDato->RetornarConsulta("select * from usuario");
             $consulta->execute();			
             return $consulta->fetchAll(PDO::FETCH_CLASS, "usuario");		
     }
 
     public static function TraerUnUsuario($id) 
     {
             $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
             $consulta =$objetoAccesoDato->RetornarConsulta("select id,usuario,password from usuario where id = $id");
             $consulta->execute();
             $usuarioBuscado= $consulta->fetchObject('usuario');
             return $usuarioBuscado;				         
     }
     public static function TraerUnUsuarioPasswordArray($usuario,$password) 
     {
             $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
             $consulta =$objetoAccesoDato->RetornarConsulta("select  id,usuario,password from usuario  WHERE usuario=:usuario AND password=:password");
             $consulta->execute(array(':usuario'=> $usuario,':password'=> $password));
             $consulta->execute();
             $cdBuscado= $consulta->fetchObject('usuario');
               return $cdBuscado;				     
     }
 
}

?>