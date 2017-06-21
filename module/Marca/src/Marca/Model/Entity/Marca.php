<?php

namespace Marca\Model\Entity;

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Adapter\Adapter;
use Zend\Db\Sql\Sql;
use Zend\Db\Sql\Select;
use Zend\Db\ResultSet\ResultSet;

class Marca extends TableGateway {

	private $dbAdapter;

	public function __construct(Adapter $adapter = null, $databaseSchema = null, ResultSet $selectResultPrototype = null) {
		$this->dbAdapter = $adapter;
		return parent::__construct('Marca', $this->dbAdapter, $databaseSchema, $selectResultPrototype);
	}

	public function insertar($descripcion) {
		$insert = $this->dbAdapter->
				createStatement("INSERT INTO marca (descripcion) VALUES (upper(trim('$descripcion')))");
		$datos = $insert->execute();
		return $datos;
	}
	public function modificar($id, $descripcion) {
		$update = $this->dbAdapter->
				createStatement(
				"update marca set descripcion=upper(trim('$descripcion'))"
						. "where id_marca=$id");
		$datos = $update->execute();
		return $datos;
	}
	
	public function lista() {
		$consulta = $this->dbAdapter->query("SELECT id_marca, descripcion, vigencia FROM marca order by vigencia desc, descripcion asc", Adapter::QUERY_MODE_EXECUTE);
		$datos = $consulta->toArray();
		return $datos;
	}

	public function buscar($id){
        $consulta=$this->dbAdapter->query("SELECT id_marca, descripcion, vigencia FROM marca where id_marca=$id",Adapter::QUERY_MODE_EXECUTE);
        $datos=$consulta->toArray();
        return $datos[0];
    }
	  public function buscarMarca($descripcion){
        $consulta=$this->dbAdapter->query(
		"SELECT id_marca as value, descripcion as label, vigencia FROM marca where descripcion like upper(trim('%$descripcion%'))",Adapter::QUERY_MODE_EXECUTE);
        $datos=$consulta->toArray();        
        return $datos;    
    }
	
	
	public function buscarMarcaModelo($descripcion){
        $consulta=$this->dbAdapter->query(
				"SELECT id_marca as value, descripcion as label, vigencia FROM marca where descripcion like upper(trim('%$descripcion%'))",Adapter::QUERY_MODE_EXECUTE);
        $datos=$consulta->toArray();        
        return $datos;    
    }
	public function eliminar($id,$vigencia){
		$insert = $this->dbAdapter->createStatement("update marca set vigencia=$vigencia where id_marca=$id");
		$datos = $insert->execute();
		return $datos;
    } 
}
