<?php

namespace Rol\Model\Entity;
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Adapter\Adapter;
use Zend\Db\Sql\Sql;
use Zend\Db\Sql\Select;

use Zend\Db\ResultSet\ResultSet;

class Rol extends TableGateway {

	private $dbAdapter;

	public function __construct(Adapter $adapter = null, $databaseSchema = null, ResultSet $selectResultPrototype = null) {
		$this->dbAdapter = $adapter;
		return parent::__construct('Rol', $this->dbAdapter, $databaseSchema, $selectResultPrototype);
	}

	public function insertar($nombre, $descripcion) {
		$insert = $this->dbAdapter->
				createStatement(
				"INSERT INTO rol (nombre, descripcion) "
						. "VALUES "
						. "(upper(trim('$nombre')),upper(trim('$descripcion')))");
		$datos = $insert->execute();
		return $datos;
	}
	
	public function modificar($nombre, $descripcion ,$id) {
		$insert = $this->dbAdapter->
				createStatement(
				"update rol set nombre=upper(trim('$nombre')), descripcion=upper(trim('$descripcion'))"
						. "where id_rol=$id");
		$datos = $insert->execute();
		return $datos;
	}


	public function lista() {
		$consulta = $this->dbAdapter->query("SELECT id_rol,descripcion,nombre,fecha_registro,vigencia FROM rol order by  vigencia desc, fecha_registro desc", Adapter::QUERY_MODE_EXECUTE);
		$datos = $consulta->toArray();
		return $datos;
	}

	public function buscar($id){
        $consulta=$this->dbAdapter->query("SELECT * FROM rol where  id_rol=$id",Adapter::QUERY_MODE_EXECUTE);
        $datos=$consulta->toArray();
        return $datos[0];
    } 

     public function buscarRol($descripcion){
        $consulta=$this->dbAdapter->query(
		"SELECT id_rol as value, descripcion as label, vigencia FROM rol where descripcion like Upper('%$descripcion%')",Adapter::QUERY_MODE_EXECUTE);
        $datos=$consulta->toArray();        
        return $datos;    
    }

	
	public function eliminar($id,$vigencia){
		$insert = $this->dbAdapter->createStatement("update rol set vigencia=$vigencia where id_rol=$id");
		$datos = $insert->execute();
		return $datos;
    } 
}