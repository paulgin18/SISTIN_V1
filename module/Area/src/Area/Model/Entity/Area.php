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

	public function insertar($descripcion, $id_uni_org,$cnx) {
		$insert = $this->dbAdapter->
				createStatement(
				"INSERT INTO area (descripcion,id_uni_org, conexion_unidad_organica) "
						. "VALUES ('$descripcion',$id_uni_org,'$cnx')");
		$datos = $insert->execute();
		return $datos;
	}
	public function modificar($id, $descripcion,$id_uni_org,$cnx) {
		
		$update = $this->dbAdapter->
				createStatement("UPDATE area SET descripcion=upper(trim('$descripcion')), conexion_unidad_organica='$cnx',id_uni_org=$id_uni_org WHERE id_area=$id");

		$datos = $update->execute();
		return $update;
	}
	
//ordenar descendente 	
//  order by vigencia desc , decripcion asc

	public function lista() {
		//$consulta = $this->dbAdapter->query("SELECT id_estado,numero,descripcion, vigencia FROM estado order by descripcion asc", Adapter::QUERY_MODE_EXECUTE);

		$consulta = $this->dbAdapter->query(
			"SELECT a.id_area, a.descripcion, a.id_uni_org, a.id_area, a.vigencia, "
				. "o.descripcion as unidad_organica FROM area "
				. "a left join unidad_organica o on a.id_uni_org=o.id_uni_org order "
				. "by a.vigencia desc", Adapter::QUERY_MODE_EXECUTE);
		$datos = $consulta->toArray();
		
		
		
		return $datos;
	}

	public function buscar($id){
        $consulta=$this->dbAdapter->
				query("SELECT a.id_area, a.descripcion, a.id_uni_org, a.id_area, a.vigencia FROM area a  where a.id_area=$id",Adapter::QUERY_MODE_EXECUTE);
        $datos=$consulta->toArray();
        return $datos[0];
    }
	
		public function buscarAreaCmb($descripcion, $idUnidadEjecutora) {
		$consulta = $this->dbAdapter->query(
				"SELECT id_area as value, a.descripcion as label,a.id_uni_org as idunidadeorg,ua.descripcion unidadorganica, a.vigencia , ua.id_uni_org FROM area a"
				. " inner join unidad_organica ua on a.id_uni_org=ua.id_uni_org "
				. " where a.vigencia=true and ua.id_uni_org=$idUnidadEjecutora and upper(a.descripcion) like upper('%$descripcion%')", Adapter::QUERY_MODE_EXECUTE);
		$datos = $consulta->toArray();
		return $datos;
	}
	
	  public function buscarArea($descripcion){
        $consulta=$this->dbAdapter->query(
		"SELECT id_area as value, descripcion as label,id_uni_org as unidadorganica, vigencia FROM area where descripcion like Upper('%$descripcion%')",Adapter::QUERY_MODE_EXECUTE);
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














