<?php
include_once '../src/AccesoDatos/AccesoDatos.php';

class Usuario{
    public $idempleado;
    public $usuario;
    public $clave;

    public static function TraerTodoLosusuarios()
	{
			$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
			$consulta =$objetoAccesoDato->RetornarConsulta("select * from usuario");
			$consulta->execute();			
			return $consulta->fetchAll(PDO::FETCH_CLASS, "usuario");		
    }
    
  	public function BorrarUsuarioPorId()
    {
              $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
             $consulta = $objetoAccesoDato->RetornarConsulta("
                 delete 
                 from usuario 				
                 WHERE idempleado=:idempleado");	
                 $consulta->bindValue(':idempleado',trim($this->idempleado), PDO::PARAM_STR);		
                 $consulta->execute();
                 return $consulta->rowCount();
    }
 
     public static function BorrarusUarioPorNombre($usuario)
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
     public function ModificarUsuario()
      {
             $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
             $consulta =$objetoAccesoDato->RetornarConsulta("
                 update usuario 
                 set usuario='$this->usuario',
                 clave='$this->clave',                 
                 WHERE idempleado='$this->idempleado'");
             return $consulta->execute();
      }
     
      public function InsertarUsuario()
      {
                 $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
                 $consulta =$objetoAccesoDato->RetornarConsulta("INSERT into cds (usuario,clave)values('$this->usuario','$this->clave')");
                 $consulta->execute();
                 return $objetoAccesoDato->RetornarUltimosexoInsertado();          
      }

       public function ModificarUsuarioParametros()
      {
             $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
             $consulta =$objetoAccesoDato->RetornarConsulta("
                 update usuario 
                 set usuario=:usuario,
                 clave=:clave
                 WHERE idempleado=:idempleado");
             $consulta->bindValue(':idempleado',$this->idempleado, PDO::PARAM_INT);
             $consulta->bindValue(':usuario',$this->usuario, PDO::PARAM_STR);
             $consulta->bindValue(':clave', $this->clave, PDO::PARAM_STR);
             return $consulta->execute();
      }
 
      public function InsertarElUsuarioParametros()
      {
                 $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
                 $consulta =$objetoAccesoDato->RetornarConsulta("INSERT into usuario (usuario,clave,idempleado)values(:usuario,:clave,:idempleado)");
                 $consulta->bindValue(':usuario', $this->usuario, PDO::PARAM_STR);
                 $consulta->bindValue(':clave', $this->clave, PDO::PARAM_STR);
                 $consulta->bindValue(':idempleado', $this->idempleado, PDO::PARAM_STR);
                 $consulta->execute();		
                 return $objetoAccesoDato->RetornarUltimoIdInsertado();
      }
      public function Guardarusuario()
      {
          if($this->idempleado>0)
              {
                  $this->ModificarusuarioParametros();
              }else {
                  $this->InsertarElusuarioParametros();
              }
      }
       public static function TraerTodoLosUsuarios2()
     {
             $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
             $consulta =$objetoAccesoDato->RetornarConsulta("select * from usuario");
             $consulta->execute();			
             return $consulta->fetchAll(PDO::FETCH_CLASS, "usuario");		
     }
 
     public static function TraerUnUsuario($idempleado) 
     {
             $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
             $consulta =$objetoAccesoDato->RetornarConsulta("select idempleado,usuario,clave from usuario where idempleado = $idempleado");
             $consulta->execute();
             $usuarioBuscado= $consulta->fetchObject('usuario');
             return $usuarioBuscado;				         
     }
}

?>