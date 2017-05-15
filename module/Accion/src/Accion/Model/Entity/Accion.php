<?php

namespace Accion\Model\Entity;

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Adapter\Adapter;
use Zend\Db\Sql\Sql;
use Zend\Db\Sql\Select;
use Zend\Db\ResultSet\ResultSet;

class Accion extends TableGateway {

	private $dbAdapter;

	public function __construct(Adapter $adapter = null, $databaseSchema = null, ResultSet $selectResultPrototype = null) {
		$this->dbAdapter = $adapter;
		return parent::__construct('Accion', $this->dbAdapter, $databaseSchema, $selectResultPrototype);
	}

	public function insertar($tipo,$descripcion) {
		$insert = $this->dbAdapter->
				createStatement(
				"INSERT INTO accion (tipo,descripcion) "
						. "VALUES ($tipo,upper(trim('$descripcion')))");
		$datos = $insert->execute();
		return $insert;
	}
	public function modificar($id,$tipo, $descripcion, $vigencia) {
		$update = $this->dbAdapter->
				createStatement(
				"update accion set descripcion=upper(trim('$descripcion')),tipo='$tipo', vigencia='$vigencia'"
						. "where id_accion=$id");
		$datos = $update->execute();
		return $update;
	}
	
	public function lista() {
		$consulta = $this->dbAdapter->query("SELECT id_accion,tipo,descripcion, vigencia FROM accion order by descripcion asc", Adapter::QUERY_MODE_EXECUTE);
		$datos = $consulta->toArray();
		return $datos;
	}

	public function buscar($id){
        $consulta=$this->dbAdapter->query("SELECT id_accion,tipo,descripcion, vigencia FROM accion where id_accion=$id",Adapter::QUERY_MODE_EXECUTE);
        $datos=$consulta->toArray();
        return $datos[0];
    }
	  public function buscarAccion($descripcion){
        $consulta=$this->dbAdapter->query(
		"SELECT id_accion as value,tipo as tipohs, descripcion as label, vigencia FROM accion where descripcion like '%$descripcion%'",Adapter::QUERY_MODE_EXECUTE);
        $datos=$consulta->toArray();        
        return $datos;    
    }



public function eliminar($id) {
		$delete = $this->dbAdapter->
				createStatement(
				"UPDATE accion set vigencia=FALSE where id_accion=$id");
		$datos = $delete->execute();
		return $delete;
	}

	//MARCA MODELO ??
	
  

	public function buscarAccionModelo($descripcion){
        $consulta=$this->dbAdapter->query(
				"SELECT id_accion as value,tipo as cantidad, descripcion as label, vigencia FROM accion where descripcion like '%$descripcion%'",Adapter::QUERY_MODE_EXECUTE);
        $datos=$consulta->toArray();        
        return $datos;    
    }
}














