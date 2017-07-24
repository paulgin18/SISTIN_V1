<?php

namespace User\Model\Entity;

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Adapter\Adapter;
use Zend\Db\Sql\Sql;
use Zend\Db\Sql\Select;

use Zend\Db\ResultSet\ResultSet;

class User extends TableGateway {

	private $dbAdapter;

	public function __construct(Adapter $adapter = null, $databaseSchema = null, ResultSet $selectResultPrototype = null) {
		$this->dbAdapter = $adapter;
		return parent::__construct('User', $this->dbAdapter, $databaseSchema, $selectResultPrototype);
	}

	public function insertar($user, $password,$id_personal,$id_rol,$id_ejecutora) {
		//bb
		$insert = $this->dbAdapter->
				createStatement("INSERT INTO usuario (usuario,password,id_personal,id_rol,id_unidad_ejecutora ) VALUES (upper(trim('$user')),'$password',$id_personal,$id_rol,$id_ejecutora)");
		$datos = $insert->execute();
		return $datos;
	}
	
	public function modificar($user, $password,$id_personal,$id_rol,$id_ejecutora,$id_user) {
		
		$insert = $this->dbAdapter->
				createStatement("update usuario set usuario=upper(trim('$user')), password='$password', id_personal=$id_personal,  
					id_rol=$id_rol, id_unidad_ejecutora=$id_ejecutora"
						. "where id_user=$id_user");
		$datos = $insert->execute();
		return $datos;
	}


	public function lista() {
		$consulta = $this->dbAdapter->query("SELECT u.id_user,u.usuario,p.nombre as nombrepersonal,p.apellido_paterno, p.apellido_materno,r.nombre as nombrerol, e.descripcion as ejecutora,u.vigencia FROM usuario u inner join personal p on u.id_personal=p.id_personal inner join rol r on u.id_rol=r.id_rol inner join unidad_ejecutora e on u.id_unidad_ejecutora=e.id_unidad_ejecutora order by  u.vigencia desc", Adapter::QUERY_MODE_EXECUTE);
		$datos = $consulta->toArray();
		return $datos;
	}

	public function buscar($id){
        $consulta=$this->dbAdapter->query("SELECT u.id_user,u.usuario,p.id_personal,(p.apellido_paterno ||' '||p.apellido_materno ||' ' || p.nombre) as personal,r.id_rol,r.nombre as rol, e.id_unidad_ejecutora,e.descripcion as ejecutora,u.vigencia FROM usuario u inner join personal p on u.id_personal=p.id_personal inner join rol r on u.id_rol=r.id_rol inner join unidad_ejecutora e on u.id_unidad_ejecutora=e.id_unidad_ejecutora where id_user=$id",Adapter::QUERY_MODE_EXECUTE);
        $datos=$consulta->toArray();
        return $datos[0];
    } 
	
	public function eliminar($id,$vigencia){
		$insert = $this->dbAdapter->createStatement("update usuario set vigencia=$vigencia where id_user=$id");
		$datos = $insert->execute();
		return $datos;
    } 
}