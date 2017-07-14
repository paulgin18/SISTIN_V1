<?php

namespace Ejecutora\Model\Entity;

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Adapter\Adapter;
use Zend\Db\Sql\Sql;
use Zend\Db\Sql\Select;
use Zend\Db\ResultSet\ResultSet;

class Ejecutora extends TableGateway {

	private $dbAdapter;

	public function __construct(Adapter $adapter = null, $databaseSchema = null, ResultSet $selectResultPrototype = null) {
		$this->dbAdapter = $adapter;
		return parent::__construct('Ejecutora', $this->dbAdapter, $databaseSchema, $selectResultPrototype);
	}

	public function insertar($descripcion,$numero) {
		$insert = $this->dbAdapter->
				createStatement(
				"INSERT INTO unidad_ejecutora (descripcion,numero)"
				."VALUES (upper(trim('$descripcion')),$numero)");
			
		$datos = $insert->execute();
		return $datos ;
	}
	public function modificar($descripcion,$numero,$id_unidad_ejecutora) {
		$update = $this->dbAdapter->
				createStatement(
				"update unidad_ejecutora set descripcion=upper(trim('$descripcion')), numero=$numero "
						. "where id_unidad_ejecutora=$id_unidad_ejecutora");
		$datos = $update->execute();
		return $datos ;
	}

	
	public function lista() {
	//	$consulta = $this->dbAdapter->query("SELECT id_red, decripcion,vigencia,id_uni_ejec FROM red order by decripcion asc", Adapter::QUERY_MODE_EXECUTE);
		$consulta = $this->dbAdapter->query("SELECT id_unidad_ejecutora, descripcion, numero, vigencia FROM unidad_ejecutora order by vigencia desc, descripcion asc", Adapter::QUERY_MODE_EXECUTE);

		$datos = $consulta->toArray();
		return $datos;
	}

	public function buscar($id){
        $consulta=$this->dbAdapter->query("SELECT  descripcion, numero, id_unidad_ejecutora FROM unidad_ejecutora where id_unidad_ejecutora=$id",Adapter::QUERY_MODE_EXECUTE);
        $datos=$consulta->toArray();
        return $datos[0];
    	
    }


	  public function buscarEjecutora($descripcion){
        $consulta=$this->dbAdapter->query(
		"SELECT id_unidad_ejecutora as value, decripcion as label, vigencia FROM unidad_ejecutora where decripcion like Upper('%$descripcion%')",Adapter::QUERY_MODE_EXECUTE);
        $datos=$consulta->toArray();        
        return $datos;    
    }


	
	public function eliminar($id,$vigencia) {
		$delete = $this->dbAdapter->
				createStatement("UPDATE unidad_ejecutora set vigencia=$vigencia where id_unidad_ejecutora=$id");
		$datos = $delete->execute();
		return $datos;
	}

}
