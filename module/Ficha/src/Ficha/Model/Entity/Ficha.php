<?php

namespace Ficha\Model\Entity;

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Adapter\Adapter;
use Zend\Db\Sql\Sql;
use Zend\Db\Sql\Select;
use Zend\Db\ResultSet\ResultSet;

class Ficha extends TableGateway {

	private $dbAdapter;

	public function __construct(Adapter $adapter = null, $databaseSchema = null, ResultSet $selectResultPrototype = null) {
		$this->dbAdapter = $adapter;
		return parent::__construct('Ficha', $this->dbAdapter, $databaseSchema, $selectResultPrototype);
	}

	public function insertar($descripcion,$tipo,$ficha, $id_marca,$id_modelo) {
		$insert = $this->dbAdapter->
				createStatement(
				"INSERT INTO disp_soft (descripcion,tipo,ficha,id_marca,id_modelo) "
						. "VALUES (upper(trim('$descripcion')),upper(trim('$tipo')), ficha='$ficha', id_marca=$id_marca, id_modelo=$id_modelo)");
		$datos = $insert->execute();
		return $insert;
	}
	public function modificar($id, $descripcion,$tipo,$ficha, $vigencia,$id_marca,$id_modelo) {
		$update = $this->dbAdapter->
				createStatement(
				"update disp_soft set descripcion=upper(trim('$descripcion')), tipo=upper(trim('$tipo')) ,"
						. " ficha='$ficha', vigencia='$vigencia' ,id_marca=$id_marca, id_modelo=$id_modelo"
						. " where id_disp_soft=$id"
						);
		$datos = $update->execute();
		return $update;
	}
	
	public function lista() {
		$consulta = $this->dbAdapter->query(
				"SELECT id_disp_soft, descripcion, tipo, ficha, vigencia  FROM disp_soft order by descripcion asc"
				, Adapter::QUERY_MODE_EXECUTE);
		$datos = $consulta->toArray();
		return $datos;
	}

	public function buscar($id){
        $consulta=$this->dbAdapter->query(
				"SELECT id_disp_soft, descripcion, tipo, ficha, vigencia  FROM disp_soft where id_disp_soft=$id"
					,Adapter::QUERY_MODE_EXECUTE);
        $datos=$consulta->toArray();
        return $datos[0];
    }
	
	public function buscarSerie($serie){
        $consulta=$this->dbAdapter->query(
				"SELECT count(serie) FROM detalle_ficha where serie=upper('$serie')"
					,Adapter::QUERY_MODE_EXECUTE);
        $datos=$consulta->toArray();
        return $datos[0];
    }
}
