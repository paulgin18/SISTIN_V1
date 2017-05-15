<?php

namespace Red\Model\Entity;

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Adapter\Adapter;
use Zend\Db\Sql\Sql;
use Zend\Db\Sql\Select;
use Zend\Db\ResultSet\ResultSet;

class Red extends TableGateway {

	private $dbAdapter;

	public function __construct(Adapter $adapter = null, $databaseSchema = null, ResultSet $selectResultPrototype = null) {
		$this->dbAdapter = $adapter;
		return parent::__construct('Red', $this->dbAdapter, $databaseSchema, $selectResultPrototype);
	}

	public function insertar($descripcion,$id_uni_ejec) {
		$insert = $this->dbAdapter->
				createStatement(
				"INSERT INTO red (decripcion,id_uni_ejec)"
				."VALUES (upper(trim('$descripcion')),$id_uni_ejec)");
			
		$datos = $insert->execute();
		return $insert;
	}
	public function modificar($id_red,$descripcion) {
		$update = $this->dbAdapter->
				createStatement(
				"update red set decripcion=upper(trim('$descripcion')) "
						. "where id_red=$id_red");
		$datos = $update->execute();
		return $update;
	}

	
	public function lista() {
		$consulta = $this->dbAdapter->query("SELECT id_red, decripcion,vigencia,id_uni_ejec FROM red order by decripcion asc", Adapter::QUERY_MODE_EXECUTE);
		$datos = $consulta->toArray();
		return $datos;
	}

	public function buscar($id){
        $consulta=$this->dbAdapter->query("SELECT  decripcion,id_red,id_uni_ejec FROM red where id_red=$id",Adapter::QUERY_MODE_EXECUTE);
        $datos=$consulta->toArray();
        return $datos[0];
    	
    }

	public function buscarRed($descripcion){
        $consulta=$this->dbAdapter->query( 
		"SELECT decripcion as label FROM red where decripcion like '%$descripcion%'",Adapter::QUERY_MODE_EXECUTE);
        $datos=$consulta->toArray();        
        return $datos;    
    }
	
	//	public function eliminar($id) {
	//	$delete = $this->dbAdapter->
	//			createStatement(
	//			"UPDATE red set vigencia=FALSE where id_uni_ejec=$id");
	//	$datos = $delete->execute();
	//	return $delete;
	//}
	
	
}
