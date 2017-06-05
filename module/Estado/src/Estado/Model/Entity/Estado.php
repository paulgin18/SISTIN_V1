<?php

namespace Estado\Model\Entity;

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Adapter\Adapter;
use Zend\Db\Sql\Sql;
use Zend\Db\Sql\Select;
use Zend\Db\ResultSet\ResultSet;

class Estado extends TableGateway {

	private $dbAdapter;

	public function __construct(Adapter $adapter = null, $databaseSchema = null, ResultSet $selectResultPrototype = null) {
		$this->dbAdapter = $adapter;
		return parent::__construct('Estado', $this->dbAdapter, $databaseSchema, $selectResultPrototype);
	}

	public function insertar($numero,$descripcion) {
		$insert = $this->dbAdapter->
				createStatement(
				"INSERT INTO estado (numero,descripcion) "
						. "VALUES ($numero,'$descripcion')");
		$datos = $insert->execute();
		return $datos;
	}
	public function modificar($id, $descripcion,$numero) {
		$update = $this->dbAdapter->
				createStatement(
				"update estado set descripcion=upper(trim('$descripcion')),numero=$numero"
						. "where id_estado=$id");
		$datos = $update->execute();
		return $update;
	}
	
//ordenar descendente 	
//  order by vigencia desc , decripcion asc

	public function lista() {
		//$consulta = $this->dbAdapter->query("SELECT id_estado,numero,descripcion, vigencia FROM estado order by descripcion asc", Adapter::QUERY_MODE_EXECUTE);


		$consulta = $this->dbAdapter->query("SELECT id_estado,numero,descripcion, vigencia FROM estado order by descripcion asc", Adapter::QUERY_MODE_EXECUTE);
		$datos = $consulta->toArray();
		return $datos;
	}

	public function buscar($id){
        $consulta=$this->dbAdapter->query("SELECT id_estado,numero,descripcion, vigencia FROM estado where id_estado=$id",Adapter::QUERY_MODE_EXECUTE);
        $datos=$consulta->toArray();
        return $datos[0];
    }
	  public function buscarEstado($descripcion){
        $consulta=$this->dbAdapter->query(
		"SELECT id_estado as value,numero as cantidad, descripcion as label, vigencia FROM estado where descripcion like '%$descripcion%'",Adapter::QUERY_MODE_EXECUTE);
        $datos=$consulta->toArray();        
        return $datos;    
    }

/*

public function eliminar($id) {
		$delete = $this->dbAdapter->
				createStatement(
				"UPDATE estado set vigencia=FALSE where id_estado=$id");
		$datos = $delete->execute();
		return $delete;
	}
*/
   
	public function eliminar($id,$vigencia) {
		$delete = $this->dbAdapter->
				createStatement("UPDATE estado set vigencia=$vigencia where id_estado=$id");
		$datos = $delete->execute();
		return $datos;
	}
	
	//MARCA MODELO ??

	public function buscarEstadoModelo($descripcion){
        $consulta=$this->dbAdapter->query(
				"SELECT id_estado as value,numero as cantidad, descripcion as label, vigencia FROM estado where descripcion like '%$descripcion%'",Adapter::QUERY_MODE_EXECUTE);
        $datos=$consulta->toArray();        
        return $datos;    
    }
}














