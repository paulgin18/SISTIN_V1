<?php

namespace Dispositivo\Model\Entity;

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Adapter\Adapter;
use Zend\Db\Sql\Sql;
use Zend\Db\Sql\Select;
use Zend\Db\ResultSet\ResultSet;

class Dispositivo extends TableGateway {

	private $dbAdapter;

	public function __construct(Adapter $adapter = null, $databaseSchema = null, ResultSet $selectResultPrototype = null) {
		$this->dbAdapter = $adapter;
		return parent::__construct('Dispositivo', $this->dbAdapter, $databaseSchema, $selectResultPrototype);
	}

	public function insertar($descripcion, $tipo, $ficha, $idDisp, $mar_mod) {
		$cnn = $this->dbAdapter->getDriver()->getConnection()->beginTransaction();
		try {
			if ($idDisp > 0) {
				$insert = $this->insertarDispositivo($idDisp, $mar_mod);
			} else {
				$sql = "INSERT INTO disp_soft (descripcion,tipo,ficha) "
						. "VALUES (upper(trim('$descripcion')),upper(trim('$tipo')), 'false');";
				$insert = $this->dbAdapter->createStatement($sql);
				$insert->execute();
				$id = $this->dbAdapter->getDriver()->getConnection()->getLastGeneratedValue("disp_soft_id_disp_soft_seq");
				if ($tipo == "DI" && $mar_mod != null) {
					$insert = $this->insertarDispositivo($id, $mar_mod);
				} else {
					$sql2 = "INSERT INTO disp_mar_mod (id_disp_soft,id_marca,id_modelo) "
							. "VALUES ($id,1,1);";
					$insert = $this->dbAdapter->createStatement($sql2);
					$insert->execute();
				}
			}
			$cnn->commit();
		} catch (\Exception $e) {
			$insert = 'Error CON: ' . $e->getMessage();
			$cnn->rollback();
		}
		return $insert;
	}

	public function insertarDispositivo($id, $mar_mod) {
		$insert = "0";
		foreach ($mar_mod as $item) {
			$id_marca = $item['id'];
			$id_modelo = $item['idModelo'];
			$sql = "INSERT INTO disp_mar_mod (id_disp_soft,id_marca,id_modelo) "
					. "VALUES ($id,$id_marca, $id_modelo);";
			$insert = $this->dbAdapter->createStatement($sql);
			$insert->execute();
		}

		return $insert;
	}

	public function modificar($id, $descripcion, $tipo, $ficha, $vigencia, $id_marca, $id_modelo) {
		$update = $this->dbAdapter->
				createStatement(
				"update disp_soft set descripcion=upper(trim('$descripcion')), tipo=upper(trim('$tipo')) ,"
				. " ficha='$ficha', vigencia='$vigencia' "
				. " where id_disp_soft=$id"
		);
		$datos = $update->execute();
		return $update;
	}

	public function lista() {
		$consulta = $this->dbAdapter->query(
				"SELECT id_disp_soft, descripcion, tipo, ficha, vigencia FROM disp_soft order by descripcion asc"
				, Adapter::QUERY_MODE_EXECUTE);
		$datos = $consulta->toArray();
		return $datos;
	}

	public function buscar($id) {
		$consulta = $this->dbAdapter->query(
				"SELECT id_disp_soft, descripcion, tipo, ficha, vigencia FROM disp_soft where id_disp_soft=$id"
				, Adapter::QUERY_MODE_EXECUTE);
		$datos = $consulta->toArray();
		return $datos[0];
	}

	public function buscarDispositivo($descripcion, $tipo) {
		$v=explode("/",$tipo);
		$sql="";
		if(isset($v[1])){ 
			$sql="SELECT id_disp_soft as value, descripcion as label, * FROM disp_soft where descripcion like upper(trim('%$descripcion%')) and tipo='$v[0]'";
		}else{
			$sql="SELECT id_disp_soft as value, descripcion as label, * FROM disp_soft where descripcion like upper(trim('%$descripcion%')) and tipo='$tipo'";
		}
		$consulta = $this->dbAdapter->query($sql, Adapter::QUERY_MODE_EXECUTE);
		$datos = $consulta->toArray();
		return $datos;
	}

	public function bMaMoxDisp($descripcion, $id_disp) {
		if ($id_disp == 3 || $id_disp == 4 || $id_disp == 5 || $id_disp == 6) {
			$sql = "Select id_disp_mar_mod as value, (m.descripcion || ' / ' || mo.descripcion|| COALESCE (('-'|| mo.capacidad),'')) as label";
		} else {
			$sql = "Select id_disp_mar_mod as value, (m.descripcion || ' / ' || mo.descripcion) as label";
		}
		$consulta = $this->dbAdapter->query(
				$sql . " from disp_mar_mod dpmm "
				. " inner join marca m on dpmm.id_marca=m.id_marca"
				. " inner join modelo mo on dpmm.id_modelo=mo.id_modelo"
				. " where (m.descripcion || ' / ' || mo.descripcion || COALESCE (('-'|| mo.capacidad),'')) like upper(trim('%$descripcion%')) and dpmm.vigencia='true' and id_disp_soft=$id_disp ", Adapter::QUERY_MODE_EXECUTE);
		$datos = $consulta->toArray();
		return $datos;
	}

	public function bMaxDisp($descripcion, $id_disp) {
		$consulta = $this->dbAdapter->query(
				"Select id_disp_mar_mod as value, m.descripcion as label from disp_mar_mod dpmm "
				. " inner join marca m on dpmm.id_marca=m.id_marca"
				. " inner join modelo mo on dpmm.id_modelo=mo.id_modelo"
				. " where m.descripcion like upper(trim('%$descripcion%')) and dpmm.vigencia='true' and id_disp_soft=$id_disp", Adapter::QUERY_MODE_EXECUTE);
		$datos = $consulta->toArray();
		return $datos;
	}

	public function bSofDisp($descripcion, $tipo) {
		$sql2 = "";
		if ($tipo == 1) {
			$sql2 = " (tipo='SC' or tipo='SM' or tipo='SL' or tipo='SO')";
		} else {
			$sql2 = " tipo='$tipo'";
		}
		$sql = "Select id_disp_mar_mod as value, s.descripcion as label, s.tipo as tipo from disp_mar_mod dpmm "
				. " inner join disp_soft s on dpmm.id_disp_soft=s.id_disp_soft"
				. " where s.descripcion like upper(trim('%$descripcion%')) and  dpmm.vigencia='true' and ". $sql2;

		$consulta = $this->dbAdapter->query($sql, Adapter::QUERY_MODE_EXECUTE);
		$datos = $consulta->toArray();
		return $datos;
	}

	public function buscarDispositivoCmb($descripcion) {
		$sql="";
			$sql="SELECT id_disp_soft as value, descripcion as label, * FROM disp_soft where (descripcion like upper('%$descripcion%'))"
					. " and vigencia=".pg_escape_string(utf8_encode('true'));
		$consulta = $this->dbAdapter->query($sql, Adapter::QUERY_MODE_EXECUTE);
		$datos = $consulta->toArray();
		return $datos;
	}
}
