<?php

namespace Unidad\Model\Entity;

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Adapter\Adapter;
use Zend\Db\Sql\Sql;
use Zend\Db\Sql\Select;
use Zend\Db\ResultSet\ResultSet;

class Unidad extends TableGateway {

	private $dbAdapter;

	public function __construct(Adapter $adapter = null, $databaseSchema = null, ResultSet $selectResultPrototype = null) {
		$this->dbAdapter = $adapter;
		return parent::__construct('Unidad', $this->dbAdapter, $databaseSchema, $selectResultPrototype);
	}

	public function insertar($descripcion) {
		$insert = $this->dbAdapter->
				createStatement(
				"INSERT INTO unidad_ejecutora (descripcion) "
						. "VALUES (upper(trim('$descripcion')))");
		$datos = $insert->execute();
		return $insert;
	}
	public function modificar($id, $descripcion) {
		$update = $this->dbAdapter->
				createStatement(
				"update unidad_ejecutora set descripcion=upper(trim('$descripcion'))"
						. "where id_uni_ejec=$id");
		$datos = $update->execute();
		return $update;
	}
	
	public function lista() {
		$consulta = $this->dbAdapter->query("SELECT id_uni_ejec, descripcion,vigencia FROM unidad_ejecutora order by descripcion asc", Adapter::QUERY_MODE_EXECUTE);
		$datos = $consulta->toArray();
		return $datos;
	}

	public function buscar($id){
        $consulta=$this->dbAdapter->query("SELECT id_uni_ejec, descripcion,vigencia FROM unidad_ejecutora where id_uni_ejec=$id",Adapter::QUERY_MODE_EXECUTE);
        $datos=$consulta->toArray();
        return $datos[0];
    }
	  public function buscarUnidad($descripcion){
        $consulta=$this->dbAdapter->query(
		"SELECT descripcion as label FROM unidad_ejecutora where descripcion like '%$descripcion%'",Adapter::QUERY_MODE_EXECUTE);
        $datos=$consulta->toArray();        
        return $datos;    
    }
	
	public function eliminar($id) {
		$delete = $this->dbAdapter->
				createStatement(
				"UPDATE unidad_ejecutora set vigencia=FALSE where id_uni_ejec=$id");
		$datos = $delete->execute();
		return $delete;
	}
	
}
