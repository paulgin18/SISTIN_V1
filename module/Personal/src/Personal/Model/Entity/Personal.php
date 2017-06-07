<?php

namespace Personal\Model\Entity;

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Adapter\Adapter;
use Zend\Db\Sql\Sql;
use Zend\Db\Sql\Select;
use Zend\Db\ResultSet\ResultSet;

class Personal extends TableGateway {

	private $dbAdapter;

	public function __construct(Adapter $adapter = null, $databaseSchema = null, ResultSet $selectResultPrototype = null) {
		$this->dbAdapter = $adapter;
		return parent::__construct('Personal', $this->dbAdapter, $databaseSchema, $selectResultPrototype);
	}

	public function insertar($nombre,$apellidos,$dni,$id_area) {
		$insert = $this->dbAdapter->
				createStatement("INSERT INTO personal (nombre,apellidos,dni,id_area) VALUES (upper(trim('$nombre')),upper(trim('$apellidos')),$dni,$id_area)");
		$datos = $insert->execute();
		return $datos;
	}
	public function modificar($id, $descripcion) {
		$update = $this->dbAdapter->
				createStatement(
				"update personal set descripcion=upper(trim('$descripcion'))"
						. "where id_marca=$id");
		$datos = $update->execute();
		return $datos;
	}
	
	public function lista() {
		$consulta = $this->dbAdapter->query("SELECT id_personal, nombre , apellidos, dni, id_area,vigencia FROM personal order by vigencia desc, apellidos asc", Adapter::QUERY_MODE_EXECUTE);
		$datos = $consulta->toArray();
		return $datos;
	}

	/*
	public function buscar($id){
        $consulta=$this->dbAdapter->query("SELECT id_personal, nombre, apellidos, dni, id_area, vigencia FROM personal where id_personal=$id",Adapter::QUERY_MODE_EXECUTE);
        $datos=$consulta->toArray();
        return $datos[0];
    }
    */

    public function buscar($id){
        $consulta=$this->dbAdapter->query("SELECT p.id_personal, p.nombre, p.apellidos, p.dni, p.id_area, p.vigencia, a.descripcion FROM personal p inner join area a on p.id_area=a.id_area where p.id_personal=$id",Adapter::QUERY_MODE_EXECUTE);
        $datos=$consulta->toArray();
        return $datos[0];
    }

	public function buscarPersonal($descripcion){
        $consulta=$this->dbAdapter->query(
		"SELECT id_personal as value, apellidos as label, vigencia FROM personal where dni like '%$descripcion%'",Adapter::QUERY_MODE_EXECUTE);
        $datos=$consulta->toArray();        
        return $datos;    
    }

	
	
	public function buscarPersonalModelo($descripcion){
        $consulta=$this->dbAdapter->query(
				"SELECT id_personal as value, apellidos as label, vigencia FROM personal where apellidos like '%$descripcion%'",Adapter::QUERY_MODE_EXECUTE);
        $datos=$consulta->toArray();        
        return $datos;    
    }
	public function eliminar($id,$vigencia){
		$insert = $this->dbAdapter->createStatement("update personal set vigencia=$vigencia where id_personal=$id");
		$datos = $insert->execute();
		return $datos;
    } 
}
