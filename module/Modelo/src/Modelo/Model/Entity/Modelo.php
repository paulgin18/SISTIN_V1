<?php

namespace Modelo\Model\Entity;

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Adapter\Adapter;
use Zend\Db\Sql\Sql;
use Zend\Db\Sql\Select;
use Zend\Db\ResultSet\ResultSet;

class Modelo extends TableGateway {

	private $dbAdapter;

	public function __construct(Adapter $adapter = null, $databaseSchema = null, ResultSet $selectResultPrototype = null) {
		$this->dbAdapter = $adapter;
		return parent::__construct('Modelo', $this->dbAdapter, $databaseSchema, $selectResultPrototype);
	}

	public function insertar($descripcion,$capacidad) {
		$insert = $this->dbAdapter->
				createStatement(
				"INSERT INTO modelo (descripcion, capacidad) "
						. "VALUES (upper(trim('$descripcion')),'$capacidad')");
		$datos = $insert->execute();
		return $insert;
	}
	public function modificar($id, $descripcion, $capacidad) {
		$update = $this->dbAdapter->
				createStatement(
				"update modelo set descripcion=upper(trim('$descripcion')), capacidad='$capacidad'"
						. "where id_modelo=$id");
		$datos = $update->execute();
		return $datos;
	}
	
	public function lista() {
		$consulta = $this->dbAdapter->query("SELECT id_modelo, descripcion, capacidad, vigencia FROM modelo order by vigencia desc, descripcion asc", Adapter::QUERY_MODE_EXECUTE);
		$datos = $consulta->toArray();
		return $datos;
	}

	public function buscar($id){
        $consulta=$this->dbAdapter->query("SELECT id_modelo, descripcion, capacidad, vigencia FROM modelo where id_modelo=$id",Adapter::QUERY_MODE_EXECUTE);
        $datos=$consulta->toArray();
        return $datos[0];
    }
	
	public function buscarModelo($descripcion){
        $consulta=$this->dbAdapter->query(
				"SELECT id_modelo as value, descripcion ||' - ' || coalesce(capacidad,'') as label, vigencia FROM modelo where descripcion like upper(trim('%$descripcion%'))",Adapter::QUERY_MODE_EXECUTE);
        $datos=$consulta->toArray();        
        return $datos;    
    }
	
	public function eliminar($id,$vigencia){
		$insert = $this->dbAdapter->createStatement("update modelo set vigencia=$vigencia where id_modelo=$id");
		$datos = $insert->execute();
		return $datos;
    } 
}
