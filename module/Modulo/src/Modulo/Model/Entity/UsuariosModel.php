<?php
namespace Modulo\Model\Entity;
 
/*
* Usamos el componente tablegateway que nos permite hacer consultas
* utilizando una capa de abstracción, aremos las consultas sobre
* una tabla que indicamos en el constructor
*/
use Zend\Db\TableGateway\TableGateway;
 
/*
* Usamos el componente Dd\Adapter que nos permite hacer consultas
* convencionales en formato SQL así como para servir de conexión 
* para el componente SQL que nos provee de una capa de abstracción
* mas potente que la que da tablagateway
*/
use Zend\Db\Adapter\Adapter;
 
/*
Usamos el componente SQL que nos permite realizar consultas
utilizando métodos.
*/
use Zend\Db\Sql\Sql;
 
/*
Igual que el anterior pero solamente con la cláusula select
*/
use Zend\Db\Sql\Select;
 
/*
* Nos da algunas herramientas para trabajar con el resulset de las consultas, puede ser prescindible
*/
use Zend\Db\ResultSet\ResultSet;
 
 
class UsuariosModel extends TableGateway{
    private $dbAdapter;
 
    public function __construct(Adapter $adapter = null, $databaseSchema = null, ResultSet $selectResultPrototype = null){
        //Conseguimos el adaptador
        $this->dbAdapter=$adapter;
         
        /*Al estar utilizando TableGateway necesitamos
        montar el constructor de la clase padre al que le  pasamos
        como parámetros principales la tabla de la base de datos que 
        corresponde a este modelo y le pasamos el adaptador de conexión
        */
        return parent::__construct('usuarios', $this->dbAdapter, $databaseSchema,$selectResultPrototype);
    }
     
    //CREAMOS LOS METODOS DEL MODELO PARA EL CRUD
     
    public function getUsuarios(){
    //Las consultas se pueden hacer de 4 formas:
     
    /* Utilizando una query en modo ejecución directamente desde el adaptador:
     
    $consulta=$this->dbAdapter->query("SELECT * FROM usuarios",Adapter::QUERY_MODE_EXECUTE);
    $datos=$consulta->toArray();
    */
     
    /* Creando una sentencia en el adaptado y ejecutándola:
     
    $consulta=$this->dbAdapter->createStatement("SELECT * FROM usuarios");
    $datos= $consulta->execute();
    */
    $consulta=$this->dbAdapter->createStatement("SELECT * FROM sem.usuarios");
    $datos= $consulta->execute();        
    /* Usando el componente SQL le pasamos el adaptador 
       y utilizamos los métodos que nos ofrece:
        
       $sql = new Sql($this->dbAdapter);
       $select = $sql->select();
       $select->from('usuarios');
        
       Aquí le indicamos que convierta las llamadas a los métodos
       en una sentencia sql y que la ejecute
       $statement = $sql->prepareStatementForSqlObject($select);
       $datos=$statement->execute();
    */
     
    //Utilizando las sentencias básicas de table gateway:
        // $select=$this->select();
        // $datos=$select->toArray();
         
        return $datos;
/*Todos estos métodos permiten hacer consultas mucho mas complejas de lo que está aquí documentado, para más información acceder a la documentación oficial.*/
    }
     
    public function getUnUsuario($id){
        // $sql = new Sql($this->dbAdapter);
        // $select = $sql->select();
        // $select->columns(array('email','password','nombre', 'apellido'))
        //        ->from('usuarios')
        //        ->where(array('id' => $id));
 
        // $selectString = $sql->getSqlStringForSqlObject($select);
        // $execute = $this->dbAdapter->query($selectString, Adapter::QUERY_MODE_EXECUTE);
        // $result=$execute->toArray();        
        // return $result[0];
        $consulta=$this->dbAdapter->query("SELECT * FROM sem.usuarios WHERE id = $id",Adapter::QUERY_MODE_EXECUTE);
        $datos=$consulta->toArray();
        
        return $datos[0];
        }
         
     // public function addUsuario($email,$password,$nombre,$apellido){
     //    $consulta=$this->dbAdapter->query("SELECT count(email) as count FROM usuarios WHERE email='$email'",Adapter::QUERY_MODE_EXECUTE);
     //    $datos=$consulta->toArray();
     //    if($datos[0]["count"]==0){
     //     $insert=$this->insert(array(
     //                        "email"    => $email,
     //                        "password" => $password,
     //                        "nombre"   => $nombre,
     //                        "apellido" => $apellido
     //                   ));
     //    }else{
     //        $insert=false;
     //    }
     //     return $insert;
     // }

     public function addUsuario($email,$password,$nombre,$apellido){
        $insert=$this->dbAdapter->createStatement("INSERT INTO sem.usuarios (email, password, nombre, apellido) VALUES ('$email', '$password', '$nombre', '$apellido')");
        $datos= $insert->execute();  
        return $insert;
     }


      
     public function deleteUsuario($id){
        $delete=$this->dbAdapter->createStatement("DELETE FROM sem.usuarios WHERE id = '$id'");
        $datos= $delete->execute();         
         return $delete;
     }

     // public function deleteUsuario($id){
     //     $delete=$this->delete(array("id"=>$id));
     //     return $delete;
     // }
      

// UPDATE sishosp.cama set estado_cama = :estadocama where idcama = :idcama

     public function updateUsuario($id,$email,$password,$nombre,$apellido){
        $update=$this->dbAdapter->createStatement("UPDATE  sem.usuarios SET email = '$email', password = '$password', nombre = '$nombre', apellido = '$apellido' WHERE id = '$id' ");
        $datos= $update->execute();
         return $update;
    }

    //  public function updateUsuario($id,$email,$password,$nombre,$apellidos){
    //      $update=$this->update(array(
    //                             "email"    => $email,
    //                             "password" => $password,
    //                             "nombre"   => $nombre,
    //                             "apellido" => $apellidos
    //                             ),
    //                             array("id"=>$id));
    //      return $update;
    // }

}