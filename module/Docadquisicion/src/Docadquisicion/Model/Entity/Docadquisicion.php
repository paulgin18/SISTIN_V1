<?php

namespace Docadquisicion\Model\Entity;
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Adapter\Adapter;
use Zend\Db\Sql\Sql;
use Zend\Db\Sql\Select;

use Zend\Db\ResultSet\ResultSet;

class Docadquisicion extends TableGateway {

	private $dbAdapter;

	public function __construct(Adapter $adapter = null, $databaseSchema = null, ResultSet $selectResultPrototype = null) {
		$this->dbAdapter = $adapter;
		return parent::__construct('Anio', $this->dbAdapter, $databaseSchema, $selectResultPrototype);
	}

	public function insertar($descripcion, $abreviatura) {
		$insert = $this->dbAdapter->
				createStatement(
				"INSERT INTO doc_adquisicion (descripcion, abreviatura) "
						. "VALUES "
						. "(upper(trim('$descripcion')),upper(trim('$abreviatura')))");
		$datos = $insert->execute();
		return $datos;
	}
	
	public function modificar($descripcion, $abreveviatura,$id) {
		$insert = $this->dbAdapter->
				createStatement(
				"update doc_adquisicion set descripcion=upper(trim('$descripcion')), abreviatura=upper(trim('$abreveviatura'))"
						. "where id_doc_adquisicion=$id");
		$datos = $insert->execute();
		return $datos;
	}


	public function lista($vigencia) {
		$where="";
		($vigencia=="true"|| $vigencia=="false")?$where=" where vigencia=$vigencia":" ";
		$consulta = $this->dbAdapter->query("SELECT id_doc_adquisicion, abreviatura, descripcion, vigencia, fecha_registro FROM doc_adquisicion$where order by vigencia desc, descripcion asc", Adapter::QUERY_MODE_EXECUTE);
		$datos = $consulta->toArray();
		return $datos;
	}

	public function buscar($id, $vigencia){
		$where="";
		($vigencia=="true"|| $vigencia=="false")?$where=" and vigencia=$vigencia":"";
        $consulta=$this->dbAdapter->query("SELECT id_doc_adquisicion, abreviatura, descripcion, vigencia, fecha_registro FROM doc_adquisicion  where id_doc_adquisicion=".$id."".$where,Adapter::QUERY_MODE_EXECUTE);
        $datos=$consulta->toArray();
        return $datos[0];
    } 
	
	public function buscarDescripcion($descripcion){
        $consulta=$this->dbAdapter->query(
				"SELECT id_doc_adquisicion as value, (abreviatura || ' - '||escripcion) as label FROM doc_adquisicion  "
		        ."where (descripcion like upper('%$descripcion%')or abreviatura like upper('%$descripcion%')) ");
        $datos=$consulta->toArray();
        return $datos[0];
    } 
	
	public function eliminar($id,$vigencia){
		$insert = $this->dbAdapter->createStatement("update doc_adquisicion set vigencia=$vigencia where id_doc_adquisicion=$id");
		$datos = $insert->execute();
		return $datos;
    } 
}