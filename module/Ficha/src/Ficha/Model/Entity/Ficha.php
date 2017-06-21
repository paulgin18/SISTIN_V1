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

	public function insertar($ficha, $numero, $fecha, $nompc, $observacion, $id_user, $id_respfuncionario, $id_resppatrimonio, $tblMicroprocesador, $tblDiscoDuro, $tblMainboard, $tblRam, $tblRed, $tblSoft, $tblOtro, $tblUser, $tblFichaDisp, $tblFichaDocAdquisicion, $tblArchivo) {
		$datos = false;
		try {
			$connection = $this->dbAdapter->getDriver()->getConnection();
			$connection->beginTransaction();
			$idInsert = $this->fichaTecnica($numero, $fecha, $nompc, $observacion, $id_user, $id_respfuncionario, $id_resppatrimonio);
			if ($ficha == 3) {
				//Solo para Fichas De quipo que no son laptops ni mucho menos desctockp
				($tblFichaDisp != null || $tblFichaDisp != "" ) ? $this->fichaDisp($idInsert, $tblFichaDisp) : "";
			} else {
				$tblMicroprocesador != null || $tblMicroprocesador != "" ? $this->microprocesador($idInsert, $tblMicroprocesador) : "";
				$tblDiscoDuro != null || $tblDiscoDuro != "" ? $this->discoDuro($idInsert, $tblDiscoDuro) : "";
				$tblMainboard != null || $tblMainboard != "" ? $this->mainboard($idInsert, $tblMainboard) : "";
				$tblRam != null || $tblRam != "" ? $this->ram($idInsert, $tblRam) : "";
				$tblRed != null || $tblRed != "" ? $this->red($idInsert, $tblRed) : "";
				$tblSoft != null || $tblSoft != "" ? $this->software($idInsert, $tblSoft) : "";
				$tblOtro != null || $tblOtro != "" ? $this->otrosComponentes($idInsert, $tblOtro) : "";
				$tblUser != null || $tblUser != "" ? $this->cuentasUsuario($idInsert, $tblUser) : "";
				$tblFichaDocAdquisicion != null || $tblFichaDocAdquisicion != "" ? $this->docAdquisicion($idInsert, $tblFichaDocAdquisicion) : "";
				$tblArchivo != null || $tblArchivo != "" ? $this->archivo($idInsert, $tblArchivo) : "";
			}
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
		($tblDiscoDuro['serie'] != "" || $tblDiscoDuro['serie'] != null || !empty($tblDiscoDuro['serie'])) ? $this->insertSerie("S", $tblDiscoDuro['serie']) : "";
		return $insert;
	}

	public function mainboard($idInsert, $tblMainboard) {
		$insert = $this->dbAdapter->
				createStatement(
				"INSERT INTO ft_compinternos(serie, id_ficha_tecnica, id_disp_mar_mod)"
				. "VALUES ('" . ($tblMainboard['serie'] != null || $tblMainboard['serie'] != '') ? $tblMainboard['serie'] : 'Sin Serie' . "', '$idInsert'," . $tblMainboard['idMainboard'] . ")");
		$insert->execute();
		($tblMainboard['serie'] != "" || $tblMainboard['serie'] != null || !empty($tblMainboard['serie'])) ? $this->insertSerie("S", $tblMainboard['serie']) : "";

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
		//$p=($tblRed['serie']!=null||$tblRed['serie'] != "" || !empty($tblRed['serie']))?"'".$tblRed['serie']."'":'null';
		$insert = $this->dbAdapter->
				createStatement(
				"INSERT INTO ft_red(serie, mac, puertos,ip, puertaenlace, proxy, id_ficha_tecnica,integrada,id_disp_mar_mod,red, internet)"
				. "VALUES (" . pg_escape_string(utf8_encode(($tblRed['serie'] != null || $tblRed['serie'] != "" || !empty($tblRed['serie'])) ? "'" . $tblRed['serie'] . "'" : 'null'))
				. ",'" . $tblRed['mac'] . "'," . pg_escape_string(utf8_encode($tblRed['puertos'] == 0 ? 'null' : $tblRed['puertos'])) . ",'"
				. $tblRed['ip'] . "','" . $tblRed['puertaenlace'] . "','" . $tblRed['proxy'] . "'," . $idInsert
				. "," . pg_escape_string(utf8_encode($tblRed['integrada'] == 1 ? 'true' : 'false'))
				. "," . pg_escape_string(utf8_encode($tblRed['id'] > 0 ? $tblRed['id'] : "null"))
				. "," . pg_escape_string(utf8_encode($tblRed['red'] == 1 ? 'true' : 'false'))
				. "," . pg_escape_string(utf8_encode($tblRed['internet'] == 1 ? 'true' : 'internet'))
				. ")");
		$insert->execute();
		($tblRed['serie'] != "" || $tblRed['serie'] != null || !empty($tblRed['serie'])) ? $this->insertSerie("S", $tblRed['serie']) : "";
		($tblRed['mac'] != "" || $tblRed['mac'] != null || !empty($tblRed['mac'])) ? $this->insertSerie("M", $tblRed['mac']) : "";
		($tblRed['ip'] != "" || $tblRed['ip'] != null || !empty($tblRed['ip'])) ? $this->insertSerie("I", $tblRed['ip']) : "";
		return $insert;
	}

	public function software($idInsert, $tblSoft) {
		foreach ($tblSoft as $soft) {
			$insert = $this->dbAdapter->
					createStatement(
					"INSERT INTO ft_software(edicion, version, licenciado, nrolicencia, tipo,id_disp_mar_mod, id_ficha_tecnica) "
					. "VALUES ('" . $soft['edicion'] . "','" . $soft['version'] . "'," . $soft['licenciado'] . ",'" . $soft['nrolicencia']//
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

	public function docAdquisicion($idInsert, $tblFichaDocAdquisicion) {
		foreach ($tblFichaDocAdquisicion as $Documento) {
			$insert = $this->dbAdapter->
					createStatement(
					"INSERT INTO ft_adquisicion(nro_doc, fecha_doc, id_doc_adquisicion,id_ficha_tecnica)"
					. "VALUES ('" . $Documento['nro_doc'] . "','" . $Documento['fecha_doc'] . "'," . $Documento['id_doc_adquisicion'] . ",$idInsert)");
			$insert->execute();
		}
		return $insert;
	}

	public function archivo($idInsert, $tblArchivo) {
		$insert = $this->dbAdapter->
				createStatement(
				"INSERT INTO ft_archivo(ruta, id_ficha_tecnica)"
				. "VALUES ('" . $tblArchivo['ruta'] . "',$idInsert)");
		$insert->execute();
		return $insert;
	}

	public function fichaDisp($idInsert, $tblFichaDisp) {
		foreach ($tblFichaDisp as $fichaDisp) {
			$insert = $this->dbAdapter->
					createStatement(
					"INSERT INTO ft_ocomponentes(serie, id_disp_mar_mod, id_ficha_tecnica,id_inventario,operativo)"
					. "VALUES ('" . $fichaDisp['serie'] . "'," . $fichaDisp['idDispMarcaModelo'] . "," . $idInsert . "," . $fichaDisp['codInventario'] . "," . $fichaDisp['operativo'] . ")");
			$insert->execute();
			($fichaDisp['serie'] != "" || $fichaDisp['serie'] != null || !empty($fichaDisp['serie'])) ? $this->insertSerie("S", $fichaDisp['serie']) : "";
		}
		return $insert;
	}

	public function cuentasUsuario($idInsert, $tblUser) {
		foreach ($tblUser as $user) {
			$insert = $this->dbAdapter->
					createStatement(
					"INSERT INTO ft_user(tipo, usuario, contrasena, id_ficha_tecnica) "
					. "VALUES ('" . $user['tipo'] . "','" . $user['user'] . "','" . $user['pass'] . "'," . $idInsert . ")");
			$insert->execute();
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
				"SELECT id_ficha_tecnica, numero, fecha_inv, nompc, observacion, fecha_registro, 
       id_user,   id_unidad_ejecutora,fecha_instalacion
       vigencia  FROM ficha_tecnica order by vigencia desc"
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
				"SELECT count(smip) FROM tmp_val where upper(smip)=upper('$serie')"
				, Adapter::QUERY_MODE_EXECUTE);
		$datos = $consulta->toArray();
		return $datos[0];
	}

	public function obtenerNumero($user, $dependencia) {
		$numero = 0;
		$consulta = $this->dbAdapter->query(
				"select (coalesce(numero,0)+1) numero from tmp_ficha where id_uni_org=$dependencia order by numero desc limit 1"
				, Adapter::QUERY_MODE_EXECUTE);
		$datos = $consulta->toArray();

		if (count($datos) === 0) {
			$numero = $this->insertNumero(-1, $user, $dependencia);
		} else {
			$consulta = $this->dbAdapter->query(
					"select numero from tmp_ficha where id_user=$user and id_uni_org=$dependencia and registro='false' order by numero desc limit 1"
					, Adapter::QUERY_MODE_EXECUTE);
			$datos2 = $consulta->toArray();
			if (count($datos2) == 0) {
				$numero = $this->insertNumero($datos[0], $user, $dependencia);
			} else {
				$numero = $datos2[0];
			}
		}

		return $numero;
	}

	public function insertNumero($cod, $user, $dependencia) {

		$d = 0;
		$numero = 0;
		if ($cod == -1) {
			$sql = "1,$user,$dependencia";
			$numero = array('numero' => 1);
		} else {
			$d = $cod['numero'];
			$sql = "$d,$user,$dependencia";
			$numero = array('numero' => $d);
		}
		$insert = $this->dbAdapter->createStatement(
				"INSERT INTO tmp_ficha(numero, id_user, id_uni_org)"
				. " VALUES ($sql)");
		$insert->execute();

		return $numero;
	}

}
