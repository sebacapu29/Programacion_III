<?php
include_once '../src/AccesoDatos/AccesoDatos.php';

class usuario{
    public $idTipo;
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
                 WHERE idTipo=:idTipo");	
                 $consulta->bindValue(':idTipo',trim($this->clave), PDO::PARAM_STR);		
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
                 WHERE idTipo='$this->idTipo'");
             return $consulta->execute();
      }
     
      public function Insertarusuario()
      {
                 $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
                 $consulta =$objetoAccesoDato->RetornarConsulta("INSERT into cds (usuario,clave)values('$this->usuario','$this->clave')");
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
                 WHERE idTipo=:idTipo");
             $consulta->bindValue(':idTipo',$this->idTipo, PDO::PARAM_INT);
             $consulta->bindValue(':usuario',$this->usuario, PDO::PARAM_STR);
             $consulta->bindValue(':clave', $this->clave, PDO::PARAM_STR);
             return $consulta->execute();
      }
 
      public function InsertarElUsuarioParametros()
      {
                 $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
                 $consulta =$objetoAccesoDato->RetornarConsulta("INSERT into usuario (usuario,clave,idTipo,perfil)values(:usuario,:clave,:idTipo,:perfil)");
                 $consulta->bindValue(':usuario', $this->usuario, PDO::PARAM_STR);
                 $consulta->bindValue(':clave', $this->clave, PDO::PARAM_STR);
                 $consulta->bindValue(':idTipo', $this->idTipo, PDO::PARAM_STR);
                 $consulta->bindValue(':perfil', $this->perfil, PDO::PARAM_STR);
                 $consulta->execute();		
                 return $objetoAccesoDato->RetornarUltimoInsertado();
      }
      public function Guardarusuario()
      {
          if($this->idTipo>0)
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
 
     public static function TraerUnusuario($idTipo) 
     {
             $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
             $consulta =$objetoAccesoDato->RetornarConsulta("select idTipo,usuario,clave from usuario where idTipo = $idTipo");
             $consulta->execute();
             $usuarioBuscado= $consulta->fetchObject('usuario');
             return $usuarioBuscado;				         
     }
     public static function TraerUnusuarioclaveArray($usuario,$clave) 
     {
             $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
             $consulta =$objetoAccesoDato->RetornarConsulta("select  idTipo,usuario,clave from usuario  WHERE usuario=:usuario AND clave=:clave");
             $consulta->execute(array(':usuario'=> $usuario,':clave'=> $clave));
             $consulta->execute();
             $cdBuscado= $consulta->fetchObject('usuario');
               return $cdBuscado;				     
     }
 
}

?>