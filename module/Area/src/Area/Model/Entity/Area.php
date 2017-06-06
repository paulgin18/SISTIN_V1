<?php

namespace Area\Model\Entity;

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Adapter\Adapter;
use Zend\Db\Sql\Sql;
use Zend\Db\Sql\Select;
use Zend\Db\ResultSet\ResultSet;

class Area extends TableGateway {

	private $dbAdapter;

	public function __construct(Adapter $adapter = null, $databaseSchema = null, ResultSet $selectResultPrototype = null) {
		$this->dbAdapter = $adapter;
		return parent::__construct('Area', $this->dbAdapter, $databaseSchema, $selectResultPrototype);
	}

	public function insertar($descripcion, $txtId_uni_ejec) {
		$insert = $this->dbAdapter->
				createStatement(
				"INSERT INTO area (descripcion,id_uni_ejec) "
						. "VALUES ('$descripcion',$txtId_uni_ejec)");
		$datos = $insert->execute();
		return $datos;
	}
	public function modificar($id, $descripcion,$id_uni_ejec) {
		
		$update = $this->dbAdapter->
				createStatement("UPDATE area SET descripcion=upper(trim('$descripcion')), id_uni_ejec=$id_uni_ejec WHERE id_area=$id");

		$datos = $update->execute();
		return $update;
	}
	
//ordenar descendente 	
//  order by vigencia desc , decripcion asc

	public function lista() {
		//$consulta = $this->dbAdapter->query("SELECT id_estado,numero,descripcion, vigencia FROM estado order by descripcion asc", Adapter::QUERY_MODE_EXECUTE);

		$consulta = $this->dbAdapter->query("SELECT id_area,descripcion,id_uni_ejec,vigencia FROM area ", Adapter::QUERY_MODE_EXECUTE);
		$datos = $consulta->toArray();
		return $datos;
	}

	public function buscar($id){
        $consulta=$this->dbAdapter->query("SELECT id_area,descripcion,id_uni_ejec FROM area where id_area=$id",Adapter::QUERY_MODE_EXECUTE);
        $datos=$consulta->toArray();
        return $datos[0];
    }
	  public function buscarArea($descripcion){
        $consulta=$this->dbAdapter->query(
		"SELECT id_area as value, descripcion as label,id_uni_ejec as unidadejecutora, vigencia FROM area where descripcion like '%$descripcion%'",Adapter::QUERY_MODE_EXECUTE);
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
				createStatement("UPDATE area set vigencia=$vigencia where id_area=$id");
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














