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

	public function insertar($ficha, $numero, $fecha, $nompc, $observacion, $idUser,
			 $tblMicroprocesador, $tblDiscoDuro, $tblMainboard, $tblRam, $tblRed, $tblSoft, 
			$tblOtro, $tblUser, $tblFichaDisp, $tblFichaDocAdquisicion, $tblArchivo,$tblPersonal,
			$tblDatosEsp,$fechaInstalacion,$idEquipo,$unidad_org) {
		$datos = false;
		try {
			$connection = $this->dbAdapter->getDriver()->getConnection();
			$connection->beginTransaction();
			$num=$this->insertNro($idUser,$unidad_org);
			
			$numero=$numero.str_pad($num, 7, '0', STR_PAD_LEFT);
			$idInsert = $this->fichaTecnica($numero, $fecha, $nompc, $observacion, $idUser,$fechaInstalacion,$idEquipo);
			$tblPersonal!= null || $tblPersonal != "" ? $this->personal($idInsert, $tblPersonal,$idUser) : "";
			$tblFechaInventario=$this->fechaInventario($fecha,$idInsert, $idUser);
			$tblDatosEsp != null || $tblDatosEsp != "" ? $this->datosEspecificos($idInsert, $tblDatosEsp) : "";
			
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
			$this->actualizarnro($numero,$unidad_org);
			$datos =array(true,$numero);
			$connection->commit();
		} catch (\Exception $e) {
			//echo $e;
		}
		return $datos;
	}

	public function actualizarnro($numero,$unidad_org) {
		$numero= split("-", $numero);
		$nro=(int)$numero[1];
		
		$insert = $this->dbAdapter->
				createStatement(
				"UPDATE tmp_ficha set registro=true where numero=$nro and id_uni_org=$unidad_org");
//var_dump($insert->getSql());
		$insert->execute();
		$datos = $this->dbAdapter->getDriver()->getConnection()->getLastGeneratedValue('ficha_tecnica_id_ficha_tecnica_seq');
		return $datos;
	}
	
	public function fichaTecnica($numero, $fecha, $nompc, $observacion, $idUser, $fechaInstalacion,$idEquipo) {
		
		$insert = $this->dbAdapter->
				createStatement(
				"INSERT INTO ficha_tecnica(numero, fecha_inv,nompc,observacion,id_user,fecha_instalacion,id_disp_soft) "
				. "VALUES ('$numero', '$fecha','$nompc', '$observacion',$idUser,'$fechaInstalacion',$idEquipo)");

//var_dump($insert->getSql());
		$insert->execute();
		$datos = $this->dbAdapter->getDriver()->getConnection()->getLastGeneratedValue('ficha_tecnica_id_ficha_tecnica_seq');
		return $datos;
	}
	public function fechaInventario($fecha,$idInsert, $idUser) {
		$insert = $this->dbAdapter->
				createStatement(
				"INSERT INTO ft_fecha_inv(fecha_inventario, id_ficha_tecnica,id_user)VALUES ('$fecha',$idInsert, $idUser)");
		$insert->execute();
		$datos = $this->dbAdapter->getDriver()->getConnection()->getLastGeneratedValue('ficha_tecnica_id_ficha_tecnica_seq');
		return $datos;
	}
	
	public function datosEspecificos($idInsert, $tblDatosEsp) {
		
		$insert = $this->dbAdapter->createStatement(
				"INSERT INTO ft_datos_especificos(compatible, id_marca, serie,operativo,garantia,fecha_adquision,anio_garantia,nropratrimonial,id_ficha_tecnica)"
				."VALUES (" .$tblDatosEsp['chkCompatible'] . ",".$tblDatosEsp['marca'].",'". $tblDatosEsp['seriePC'] . "',". $tblDatosEsp['chkOpOtros'] .","
				.$tblDatosEsp['chkGarantia']. ",'". $tblDatosEsp['fechaAdquisicion']."',".$tblDatosEsp['anioGarantia'].",'".$tblDatosEsp['nroPatrimonio']
				. "',".$idInsert.")");
		$insert->execute();
//COALESCE(".$tblDatosEsp['marca'].",null)
		return $insert;
	}
	
	public function personal($idInsert, $tblPersonal,$idUser) {
		$insert = $this->dbAdapter->createStatement(
				"INSERT INTO ft_resp_area(id_respfuncionario, id_user, id_reppatrimonio,id_area,id_uni_org,id_ficha_tecnica)"
				."VALUES (" . $tblPersonal['resFuncionario'] . ",".$idUser.",". $tblPersonal['resPatrimonio'] . ",". $tblPersonal['areaServ'] . ",". $tblPersonal['unidadOrganica'] 
				. ",".$idInsert.")");
		$insert->execute();
		return $insert;
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
		$sql="INSERT INTO ft_compinternos(serie, id_ficha_tecnica, id_disp_mar_mod)"
				. "VALUES (".($tblMainboard['serie'] != "" || $tblMainboard['serie'] != null || !empty($tblMainboard['serie']))
				?"'".$tblMainboard['serie'].","
				:""
				
				.", '$idInsert'," . $tblMainboard['idMainboard'] . ")";
		
		$insert = $this->dbAdapter->
				createStatement(
				"INSERT INTO ft_compinternos(serie, id_ficha_tecnica, id_disp_mar_mod)"
				. "VALUES ('".$tblMainboard['serie'] ."', '$idInsert'," . $tblMainboard['idMainboard'] . ")");
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
		echo "aaa";
		return $insert;
	}

	public function red($idInsert, $tblRed) {
		//$p=($tblRed['serie']!=null||$tblRed['serie'] != "" || !empty($tblRed['serie']))?"'".$tblRed['serie']."'":'null';
		$insert = $this->dbAdapter->
				createStatement(
				"INSERT INTO ft_red(serie, mac, puertos,ip, puertaenlace, proxy, id_ficha_tecnica,integrada,id_disp_mar_mod,red, internet)"
				. "VALUES ('" . $tblRed['serie']
				. "','" . $tblRed['mac'] . "'," . pg_escape_string(utf8_encode($tblRed['puertos'] == 0 ? 'null' : $tblRed['puertos'])) . ",'"
				. $tblRed['ip'] . "','" . $tblRed['puertaenlace'] . "','" . $tblRed['proxy'] . "'," . $idInsert
				. "," . pg_escape_string(utf8_encode($tblRed['integrada'] == 1 ? 'true' : 'false'))
				. "," . pg_escape_string(utf8_encode($tblRed['id'] > 0 ? $tblRed['id'] : "null"))
				. "," . pg_escape_string(utf8_encode($tblRed['red'] == 1 ? 'true' : 'false'))
				. "," . pg_escape_string(utf8_encode($tblRed['internet'] == 1 ? 'true' : 'false'))
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
			($soft['nrolicencia'] != "" || $soft['nrolicencia'] != null || !empty($soft['nrolicencia'])) ? $this->insertSerie("S", $soft['nrolicencia']) : "";
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
			$fa= empty($fichaDisp['fadquisicion'])? pg_escape_string(utf8_encode('null')):"'".$fichaDisp['fadquisicion']."'";
			$fr= empty($fichaDisp['frenovacion'])? pg_escape_string(utf8_encode('null')):"'".$fichaDisp['frenovacion']."'";
			
			$insert = $this->dbAdapter->
					createStatement(
					"INSERT INTO ft_ocomponentes(serie, id_disp_mar_mod, id_ficha_tecnica,id_inventario,operativo,imei,fecha_renovacion,fecha_adquisicion)"
					. "VALUES ('" . $fichaDisp['serie'] . "'," . $fichaDisp['idDispMarcaModelo'] . "," . $idInsert . ",'" . $fichaDisp['codInventario'] . "'," . $fichaDisp['operativo'] . ",'" . $fichaDisp['imei'] . "'," . $fr . "," . $fa . ")");
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
				"SELECT id_ficha_tecnica, numero, fecha_inv, nompc, observacion, fecha_registro,id_disp_soft,
					fu_bdispositivo(id_disp_soft::int) dispositivo, fu_brespfuncionanrio(id_ficha_tecnica) funcionario,
					id_user,id_unidad_ejecutora,fecha_instalacion,vigencia  FROM ficha_tecnica order by vigencia desc"
				, Adapter::QUERY_MODE_EXECUTE);
		$datos = $consulta->toArray();
		return $datos;
	}

	public function buscarFicha($id) {
		$consulta = $this->dbAdapter->query(
		"SELECT id_ficha_tecnica, fecha_inv, coalesce(trim(nompc),null) nompc, coalesce(trim(observacion),null) observacion, fecha_registro,"
		. "id_user, id_anio, vigencia, id_unidad_ejecutora, fecha_instalacion, "
		. "numero, fecha_actualizacion, id_disp_soft, upper(fu_bdispositivo(id_disp_soft)) dispositivo FROM ficha_tecnica "
		. " where id_ficha_tecnica=$id"
				, Adapter::QUERY_MODE_EXECUTE);
		$datos = $consulta->toArray();
		return $datos!=null?$datos[0]:null;
	}
	
	public function bAdquisicion($id) {
		$consulta = $this->dbAdapter->query(
		"SELECT id_ft_adquisicion, nro_doc, fecha_doc, fecha_registro, "
		."vigencia, id_doc_adquisicion, fu_bdocadquisicion(id_doc_adquisicion::int) doc, id_ficha_tecnica "
		."FROM ft_adquisicion where id_ficha_tecnica=$id"
				, Adapter::QUERY_MODE_EXECUTE);
		$datos = $consulta->toArray();
		return $datos;
	}
	
	public function bArchivo($id) {
		$consulta = $this->dbAdapter->query(
		"SELECT id_ft_archivo, trim(ruta), vigencia, fecha_registro, id_ficha_tecnica  "
				. "FROM ft_archivo where id_ficha_tecnica=$id"
				, Adapter::QUERY_MODE_EXECUTE);
		$datos = $consulta->toArray();
		return $datos;
	}
	
		public function bComponenteInterno($id,$idDis) {
		$consulta = $this->dbAdapter->query(
		"SELECT fi.id_detalle_ficha, trim(fi.serie) serie, trim(fi.estructura) estructura, fi.vigencia, "
				. "fi.aniogarantia,fi.id_ficha_tecnica, fi.id_disp_mar_mod, "
				. "dm.id_marca, fu_bmarca(dm.id_marca::int) marca, dm.id_modelo, trim(fu_bmodelo(dm.id_modelo::int)) modelo, "
				. "id_disp_soft, trim(fu_bdispositivo(id_disp_soft::int)) dispositivo "
				. " FROM ft_compinternos fi "
				. "inner join disp_mar_mod dm on fi.id_disp_mar_mod=dm.id_disp_mar_mod "
				. " where fi.id_ficha_tecnica=$id and dm.id_disp_soft=$idDis and fi.vigencia=true" 
			, Adapter::QUERY_MODE_EXECUTE);
		$datos = $consulta->toArray();
		//var_dump($datos);
		return $datos!=null?$datos[0]:null;
	}
	
	public function bComponenteInternoRam($id,$idDis) {
		$consulta = $this->dbAdapter->query(
		"SELECT fi.id_detalle_ficha, trim(fi.serie) serie, trim(fi.estructura) estructura, fi.vigencia, "
				. "fi.aniogarantia,fi.id_ficha_tecnica, fi.id_disp_mar_mod, "
				. "dm.id_marca, fu_bmarca(dm.id_marca::int) marca, dm.id_modelo, trim(fu_bmodelo(dm.id_modelo::int)) modelo, "
				. "id_disp_soft, trim(fu_bdispositivo(id_disp_soft::int)) dispositivo "
				. " FROM ft_compinternos fi "
				. "inner join disp_mar_mod dm on fi.id_disp_mar_mod=dm.id_disp_mar_mod "
				. " where fi.id_ficha_tecnica=$id and dm.id_disp_soft=$idDis and fi.vigencia=true" 
			, Adapter::QUERY_MODE_EXECUTE);
		$datos = $consulta->toArray();
		//var_dump($datos);
		return $datos!=null?$datos:null;
	}
	public function bDatosEspecificos($id) {
		$consulta = $this->dbAdapter->query(
		"SELECT id_ft_datos_esp, compatible, id_marca, fu_bmarca(id_marca::int) marca,serie, operativo,"
				. " garantia, fecha_adquision, anio_garantia, nropratrimonial, "
				. " id_ficha_tecnica  FROM ft_datos_especificos "
				. "where id_ficha_tecnica=$id" 
			, Adapter::QUERY_MODE_EXECUTE);
		$datos = $consulta->toArray();
		return $datos!=null?$datos[0]:null;
	}
	
	public function bFechaInventario($id) {
		$consulta = $this->dbAdapter->query(
		"SELECT id_ft_fecha_inv, fecha_inventario, vigencia, id_user, "
				. "id_ficha_tecnica FROM ft_fecha_inv where id_ficha_tecnica=$id" 
			, Adapter::QUERY_MODE_EXECUTE);
		$datos = $consulta->toArray();
		return $datos[0];
	}
	
	public function bOComponenentes($id) {
		$consulta = $this->dbAdapter->query(
		"SELECT id_ft_ocomponentes, serie, vigencia, id_disp_mar_mod, "
				. "id_ficha_tecnica, operativo, id_inventario, imei, "
				. "fecha_renovacion, fecha_adquisicion FROM ft_ocomponentes "
				. "where id_ficha_tecnica=$id"
			, Adapter::QUERY_MODE_EXECUTE);
		$datos = $consulta->toArray();
		return $datos;
	}
	
	public function bRed($id) {
		$consulta = $this->dbAdapter->query(
		"SELECT id_ft_red, serie, mac, puertos, trim(ip), trim(puertaenlace), "
		. "trim(proxy), integrada, vigencia, red, internet, id_disp_mar_mod, "
		. "id_ficha_tecnica FROM ft_red where id_ficha_tecnica=$id"
			, Adapter::QUERY_MODE_EXECUTE);
		$datos = $consulta->toArray();
		return $datos!=null?$datos[0]:null;
	}
	
	public function bResArea($id) {
		$consulta = $this->dbAdapter->query(
		"SELECT id_ft_detalle, fecha_registro, vigencia, id_user,"
	. "id_anio, id_respfuncionario,fu_brespfuncionanrio(id_ficha_tecnica) funcionario,"
	. " id_reppatrimonio, fu_brespatrimonial(id_uni_org::int,id_area::int) respatrimonial, "
	. "id_area, fu_barea(id_area::int) area, id_uni_org, fu_bunidadorganica(id_area::int) unio_rg, id_uni_eje, id_ficha_tecnica "
	. " FROM ft_resp_area where id_ficha_tecnica=$id and  vigencia=true order by fecha_registro desc limit 1"
			, Adapter::QUERY_MODE_EXECUTE);
		$datos = $consulta->toArray();
		return $datos!=null?$datos[0]:null;
	}
	
	public function bSoftware($id) {
		$consulta = $this->dbAdapter->query(
		"SELECT id_ft_software, edicion, version, licenciado, nrolicencia, "
				. "tipo, fechainstall, aniovigente, vigencia, id_disp_mar_mod, "
				. "id_ficha_tecnica FROM ft_software"
				. " where id_ficha_tecnica=$id"
			, Adapter::QUERY_MODE_EXECUTE);
		$datos = $consulta->toArray();
		return $datos!=null?$datos[0]:null;
	}
	public function bUser($id) {
		$consulta = $this->dbAdapter->query(
		"SELECT id_ft_user, tipo, usuario, contrasena, id_ficha_tecnica,vigencia "
				. "FROM ft_user where id_ficha_tecnica=$id"
			, Adapter::QUERY_MODE_EXECUTE);
		$datos = $consulta->toArray();
		return $datos;
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
//var_dump($datos);
		if (count($datos) === 0) {
			$numero = $this->insertNumero(-1, $user, $dependencia);
		} else {
			$consulta = $this->dbAdapter->query(
					"select numero from tmp_ficha where id_uni_org=$dependencia and registro='false' order by numero desc limit 1"
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
			$sql = "1,$dependencia";
			$numero = array('numero' => 1);
		} else {
			$d = $cod['numero'];
			$sql = "$d,$dependencia";
			$numero = array('numero' => $d);
		}
		$insert = $this->dbAdapter->createStatement(
				"INSERT INTO tmp_ficha(numero,  id_uni_org)"
				. " VALUES ($sql)");
		$insert->execute();

		return $numero;
	}

	
	public function insertNro($idUser,$idUni) {
		$insert = $this->dbAdapter->createStatement(
				"INSERT INTO tmp_ficha(registro,  id_user,id_uni_org)"
				. " VALUES (true,$idUser,$idUni)");
		$insert->execute();
		$datos = $this->dbAdapter->getDriver()->getConnection()->getLastGeneratedValue('tmp_ficha_numero_seq');
		return $datos;
	}
}
