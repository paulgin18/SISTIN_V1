<?php

namespace Situacion\Model\Entity;
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Adapter\Adapter;
use Zend\Db\Sql\Sql;
use Zend\Db\Sql\Select;

use Zend\Db\ResultSet\ResultSet;

class Situacion extends TableGateway {

	private $dbAdapter;

	public function __construct(Adapter $adapter = null, $databaseSchema = null, ResultSet $selectResultPrototype = null) {
		$this->dbAdapter = $adapter;
		return parent::__construct('Situacion', $this->dbAdapter, $databaseSchema, $selectResultPrototype);
	}

	public function insertar($descripcion,$id_dispositivo) {
		$insert = $this->dbAdapter->
				createStatement("INSERT INTO situacion (descripcion,id_dispositivo) VALUES (upper(trim('$descripcion')),$id_dispositivo)");

			
		$datos = $insert->execute();
		return $datos;
	}
	
	public function modificar($descripcion,$id_dispositivo,$id_situacion) {
		$insert = $this->dbAdapter->
				createStatement(
				"update situacion set descripcion=upper(trim('$descripcion')), id_dispositivo=$id_dispositivo "
						. "where id_situacion=$id_situacion");
		$datos = $insert->execute();
		return $datos;
	}


	public function lista() {
		$consulta = $this->dbAdapter->query("SELECT s.id_situacion, s.descripcion, s.id_dispositivo, s.vigencia, d.descripcion as dispositivo FROM situacion s inner join disp_soft d on s.id_dispositivo=d.id_disp_soft order by s.vigencia desc", Adapter::QUERY_MODE_EXECUTE);
		$datos = $consulta->toArray();
		return $datos;
	}

	public function buscar($id){
        $consulta=$this->dbAdapter->query("SELECT s.id_situacion, s.descripcion as situacion, s.vigencia, s.id_dispositivo, d.descripcion as dispositivo FROM situacion s 
        	inner join disp_soft d on s.id_dispositivo=d.id_disp_soft where id_situacion=$id",Adapter::QUERY_MODE_EXECUTE);
        $datos=$consulta->toArray();
        return $datos[0];
    } 
	
	public function eliminar($id,$vigencia){
		$insert = $this->dbAdapter->createStatement("update situacion set vigencia=$vigencia where id_situacion=$id");
		$datos = $insert->execute();
		return $datos;
    } 
}