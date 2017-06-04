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

	public function insertar($numero, $fecha, $nompc, $observacion, $id_user, $id_respfuncionario, $id_resppatrimonio, $tblMicroprocesador, $tblDiscoDuro, $tblMainboard, $tblRam, $tblRed, $tblSoft, $tblOtro) {
		$datos = false;
		try {
			$connection = $this->dbAdapter->getDriver()->getConnection();
			$connection->beginTransaction();
			$idInsert = $this->fichaTecnica($numero, $fecha, $nompc, $observacion, $id_user, $id_respfuncionario, $id_resppatrimonio);
			$tblMicroprocesador != null || $tblMicroprocesador != "" ? $this->microprocesador($idInsert, $tblMicroprocesador) : "";
			$tblDiscoDuro != null || $tblDiscoDuro != "" ? $this->discoDuro($idInsert, $tblDiscoDuro) : "";
			$tblMainboard != null || $tblMainboard != "" ? $this->mainboard($idInsert, $tblMainboard) : "";
			$tblRam != null || $tblRam != "" ? $this->ram($idInsert, $tblRam) : "";
			$tblRed != null || $tblRed != "" ? $this->red($idInsert, $tblRed) : "";
			$tblSoft != null || $tblSoft != "" ? $this->software($idInsert, $tblSoft) : "";
			$tblOtro != null || $tblOtro != "" ? $this->otrosComponentes($idInsert, $tblOtro) : "";
			$connection->commit();
			$datos = true;
		} catch (\Exception $e) {
			echo $e;
		}
		return $datos;
	}

	public function fichaTecnica($numero, $fecha, $nompc, $observacion, $id_user, $id_respfuncionario, $id_resppatrimonio) {
		$insert = $this->dbAdapter->
				createStatement(
				"INSERT INTO ficha_tecnica(numero, fecha,nompc,observacion,id_user, id_respfuncionario, id_resppatrimonio) "
				. "VALUES ($numero, '$fecha','$nompc', '$observacion',$id_user, $id_respfuncionario, $id_resppatrimonio)");
		$insert->execute();
		$datos = $this->dbAdapter->getDriver()->getConnection()->getLastGeneratedValue('ficha_tecnica_id_ficha_tecnica_seq');
		return $datos;
	}

	public function microprocesador($idInsert, $tblMicroprocesador) {

		$insert = $this->dbAdapter->createStatement(
				"INSERT INTO ft_compinternos(estructura, id_ficha_tecnica, id_disp_mar_mod)"
				. "VALUES ('" . $tblMicroprocesador['estructura'] . "', '$idInsert'," . $tblMicroprocesador['idMicroprocesador'] . ")");
		$insert->execute();

		return $insert;
	}

	public function discoDuro($idInsert, $tblDiscoDuro) {
		$insert = $this->dbAdapter->
				createStatement(
				"INSERT INTO ft_compinternos(serie, id_ficha_tecnica, id_disp_mar_mod)"
				. "VALUES ('" . $tblDiscoDuro['serie'] . "', '$idInsert'," . $tblDiscoDuro['idDiscoDuro'] . ")");
		$insert->execute();
		return $insert;
	}

	public function mainboard($idInsert, $tblMainboard) {
		$insert = $this->dbAdapter->
				createStatement(
				"INSERT INTO ft_compinternos(serie, id_ficha_tecnica, id_disp_mar_mod)"
				. "VALUES ('" . ($tblMainboard['serie'] != null || $tblMainboard['serie'] != '') ? $tblMainboard['serie'] : 'Sin Serie' . "', '$idInsert'," . $tblMainboard['idMainboard'] . ")");
		$insert->execute();
		($tblMainboard['serie'] != "" || $tblMainboard['serie'] != null|| !empty($tblMainboard['serie'])) ? $this->insertSerie("S", $tblMainboard['serie']) : "";

		return $insert;
	}

	public function ram($idInsert, $tblRam) {
		foreach ($tblRam as $ram) {
			$insert = $this->dbAdapter->
					createStatement(
					"INSERT INTO ft_compinternos( id_ficha_tecnica, id_disp_mar_mod)"
					. "VALUES ('$idInsert'," . $ram['id'] . ")");
			$insert->execute();
		}
		return $insert;
	}

	public function red($idInsert, $tblRed) {
		$insert = $this->dbAdapter->
				createStatement(
				"INSERT INTO ft_red(serie, mac, puertos, ip, puertaenlace, proxy, integrada,id_disp_mar_mod, id_ficha_tecnica, conectividad)"
				. "VALUES ('" . $tblRed['serie'] . "','" . $tblRed['mac'] . "'," . $tblRed['puertos'] == 0 ? null : $tblRed['puertos'] . ",'" . $tblRed['ip'] . "','"
				. $tblRed['puertaEnlace'] . "','" . $tblRed['proxy']
				. "'," . $tblRed['integrada'] == 1 ? true : false . "," . $tblRed['id'] . "$idInsert)");
		$insert->execute();
		($tblRed['serie'] != "" || $tblRed['serie'] != null || !empty($tblRed['serie'])) ? $this->insertSerie("S", $tblRed['serie']) : "";
		($tblRed['mac'] != "" || $tblRed['mac'] != null || !empty($tblRed['mac'])) ? $this->insertSerie("M", $tblRed['mac']) : "";
		($tblRed['ip'] != "" || $tblRed['ip'] != null|| !empty($tblRed['ip'])) ? $this->insertSerie("I", $tblRed['ip']) : "";
		return $insert;
	}

	public function software($idInsert, $tblSoft) {
		foreach ($tblSoft as $soft) {
			$insert = $this->dbAdapter->
					createStatement(
					"INSERT INTO ft_software(edicion, version, licenciado, nrolicencia, tipo,id_disp_mar_mod, id_ficha_tecnica) "
					. "VALUES ('" . $soft['edicion'] . "','" . $soft['version'] . "'," . $soft['licenciado'] . ",'" . $soft['nrolicencia']
					. "','" . $soft['tipo'] . "'," . $soft['id'] . ",$idInsert)");
			$insert->execute();
			($soft['nrolicencia'] != "" || $soft['nrolicencia'] != null || !empty(soft['nrolicencia'])) ? $this->insertSerie("S", $soft['nrolicencia']) : "";
		}
		return $insert;
	}

	public function otrosComponentes($idInsert, $tblOtro) {
		foreach ($tblOtro as $otro) {
			$insert = $this->dbAdapter->
					createStatement(
					"INSERT INTO ft_ocomponentes(serie, id_disp_mar_mod, id_ficha_tecnica)"
					. "VALUES ('" . $otro['serie'] . "'," . $otro['id'] . ",$idInsert)");
			$insert->execute();
			($otro['serie'] != "" || $otro['serie'] != null || !empty($otro['serie'])) ? $this->insertSerie("S", $otro['serie']) : "";
		}
		return $insert;
	}

	public function insertSerie($tipo, $serie) {
		$insert = $this->dbAdapter->createStatement(
				"INSERT INTO tmp_val(tipo,smip)"
				. "VALUES ('$tipo','$serie')");
		$insert->execute();
	}

	public function modificar($id, $descripcion, $tipo, $ficha, $vigencia, $id_marca, $id_modelo) {
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

	public function buscar($id) {
		$consulta = $this->dbAdapter->query(
				"SELECT id_disp_soft, descripcion, tipo, ficha, vigencia  FROM disp_soft where id_disp_soft=$id"
				, Adapter::QUERY_MODE_EXECUTE);
		$datos = $consulta->toArray();
		return $datos[0];
	}

	public function buscarSerie($serie) {
		$consulta = $this->dbAdapter->query(
				"SELECT count(serie) FROM tmp_serie where serie=upper('$serie')"
				, Adapter::QUERY_MODE_EXECUTE);
		$datos = $consulta->toArray();
		return $datos[0];
	}

}
