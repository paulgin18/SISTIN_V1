<?php

namespace Anio\Model\Entity;
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Adapter\Adapter;
use Zend\Db\Sql\Sql;
use Zend\Db\Sql\Select;

use Zend\Db\ResultSet\ResultSet;

class Anio extends TableGateway {

	private $dbAdapter;

	public function __construct(Adapter $adapter = null, $databaseSchema = null, ResultSet $selectResultPrototype = null) {
		$this->dbAdapter = $adapter;
		return parent::__construct('Anio', $this->dbAdapter, $databaseSchema, $selectResultPrototype);
	}

	public function insertar($descripcion, $numero) {
		$insert = $this->dbAdapter->
				createStatement(
				"INSERT INTO anio (descripcion, numero) "
						. "VALUES "
						. "(upper(trim('$descripcion')),$numero)");
		$datos = $insert->execute();
		return $datos;
	}
	
	public function modificar($descripcion, $numero,$id) {
		$insert = $this->dbAdapter->
				createStatement(
				"update anio set descripcion=upper(trim('$descripcion')), numero=$numero "
						. "where id_anio=$id");
		$datos = $insert->execute();
		return $datos;
	}


	public function lista() {
		$consulta = $this->dbAdapter->query("SELECT id_anio,numero,descripcion,vigencia FROM anio order by  vigencia desc, numero desc", Adapter::QUERY_MODE_EXECUTE);
		$datos = $consulta->toArray();
		return $datos;
	}

	public function buscar($id){
        $consulta=$this->dbAdapter->query("SELECT * FROM anio where  id_anio=$id",Adapter::QUERY_MODE_EXECUTE);
        $datos=$consulta->toArray();
        return $datos[0];
    } 
	
	public function eliminar($id){
		$insert = $this->dbAdapter->createStatement("update anio set vigencia=false where id_anio=$id");
		$datos = $insert->execute();
		return $datos;
    } 
}