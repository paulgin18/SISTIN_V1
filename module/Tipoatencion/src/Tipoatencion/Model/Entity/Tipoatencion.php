<?php

namespace Tipoatencion\Model\Entity;

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Adapter\Adapter;
use Zend\Db\Sql\Sql;
use Zend\Db\Sql\Select;
use Zend\Db\ResultSet\ResultSet;

class Tipoatencion extends TableGateway {

	private $dbAdapter;

	public function __construct(Adapter $adapter = null, $databaseSchema = null, ResultSet $selectResultPrototype = null) {
		$this->dbAdapter = $adapter;
		return parent::__construct('Tipoatencion', $this->dbAdapter, $databaseSchema, $selectResultPrototype);
	}

	public function insertar($numero,$descripcion) {
		$insert = $this->dbAdapter->
				createStatement(
				"INSERT INTO tipo_atencion (numero,descripcion) "
						. "VALUES ($numero,upper(trim('$descripcion')))");
		$datos = $insert->execute();
		return $insert;
	}
	public function modificar($id, $descripcion,$numero, $vigencia) {
		$update = $this->dbAdapter->
				createStatement(
				"update tipo_atencion set descripcion=upper(trim('$descripcion')),numero='$numero', vigencia='$vigencia'"
						. "where id_tipo_atencion=$id");
		$datos = $update->execute();
		return $update;
	}
	
	public function lista() {
		$consulta = $this->dbAdapter->query("SELECT id_tipo_atencion,numero,descripcion, vigencia FROM tipo_atencion order by vigencia desc", Adapter::QUERY_MODE_EXECUTE);
		$datos = $consulta->toArray();
		return $datos;
	}

	public function buscar($id){
        $consulta=$this->dbAdapter->query("SELECT id_tipo_atencion,numero,descripcion, vigencia FROM tipo_atencion where id_tipo_atencion=$id",Adapter::QUERY_MODE_EXECUTE);
        $datos=$consulta->toArray();
        return $datos[0];
    }
	  public function buscarTipoatencion($descripcion){
        $consulta=$this->dbAdapter->query(
		"SELECT id_tipo_atencion as value,numero as cantidad, descripcion as label, vigencia FROM tipo_atencion where descripcion like '%$descripcion%'",Adapter::QUERY_MODE_EXECUTE);
        $datos=$consulta->toArray();        
        return $datos;    
    }



public function eliminar($id,$vigencia) {
		$delete = $this->dbAdapter->
				createStatement(
				"UPDATE tipo_atencion set vigencia=$vigencia where id_tipo_atencion=$id");
		$datos = $delete->execute();
		return $delete;
	}


	//MARCA MODELO ??
  

	public function buscarTipoatencionModelo($descripcion){
        $consulta=$this->dbAdapter->query(
				"SELECT id_tipo_atencion as value,numero as cantidad, descripcion as label, vigencia FROM tipo_atencion where descripcion like '%$descripcion%'",Adapter::QUERY_MODE_EXECUTE);
        $datos=$consulta->toArray();        
        return $datos;    
    }
}














