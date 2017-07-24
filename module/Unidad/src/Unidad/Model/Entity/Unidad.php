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

	public function insertar($descripcion, $numero, $id_ejecutora) {
		$insert = $this->dbAdapter->
				createStatement("INSERT INTO unidad_organica (descripcion, numero, unidad_organica_id_uni_org) "
					. "VALUES (upper(trim('$descripcion')), upper(trim('$numero')),".pg_escape_string(utf8_encode($id_ejecutora>0?$id_ejecutora:"null")).")");
		$datos = $insert->execute();
		return $insert;
	}
	
	public function modificar($id, $descripcion) {
		$update = $this->dbAdapter->
				createStatement(
				"update unidad_organica set descripcion=upper(trim('$descripcion'))"
						. "where id_uni_org=$id");
		$datos = $update->execute();
		return $update;
	}
	
	public function lista() {
		$consulta = $this->dbAdapter->query("SELECT id_uni_org, descripcion, numero, vigencia, unidad_organica_id_uni_org FROM unidad_organica order by vigencia desc , descripcion asc", Adapter::QUERY_MODE_EXECUTE);
		$datos = $consulta->toArray();
		return $datos;
	}

	public function buscar($id){
        $consulta=$this->dbAdapter->query("SELECT id_uni_org, descripcion, numero, vigencia, unidad_organica_id_uni_org,id_unidad_ejecutora, fu_bunidadejecutora(id_unidad_ejecutora) unidad_ejecutora  FROM unidad_organica where id_uni_org=$id",Adapter::QUERY_MODE_EXECUTE);
        $datos=$consulta->toArray();
        return $datos[0];
    }
	  public function buscarUnidad($descripcion){
        $consulta=$this->dbAdapter->query(
		"SELECT descripcion as label FROM unidad_organica where descripcion like '%$descripcion%'",Adapter::QUERY_MODE_EXECUTE);
        $datos=$consulta->toArray();        
        return $datos;    
    }
	
	public function eliminar($id,$vigencia) {
		$delete = $this->dbAdapter->
				createStatement(
				"UPDATE unidad_organica set vigencia=$vigencia where id_uni_org=$id");
		$datos = $delete->execute();
		return $delete;
	}
	
		public function buscarUnidadCmb($descripcion) {
		$sql="";
			$sql="SELECT id_uni_org as value, descripcion as label, * FROM unidad_organica where (descripcion like upper('%$descripcion%')or numero like upper('%$descripcion%'))"
					. " and vigencia=".pg_escape_string(utf8_encode('true'));
		$consulta = $this->dbAdapter->query($sql, Adapter::QUERY_MODE_EXECUTE);
		$datos = $consulta->toArray();
		return $datos;
	}
	
}
