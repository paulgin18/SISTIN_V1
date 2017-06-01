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

	public function insertar($numero,$fecha,$nompc, $observacion,$id_user, 
			$id_respfuncionario,$id_resppatrimonio, $tblMicroprocesador, $tblDiscoDuro,$tblMainboard,$tblRam,$tblRed,$tblSoft,$tblOtro) {
		try{
		$connection = $this->dbAdapter->getDriver()->getConnection();
		$connection->beginTransaction();
		$idInsert=$this->fichaTecnica($numero,$fecha,$nompc, $observacion,$id_user, $id_respfuncionario,$id_resppatrimonio);
		$this->microprocesador($idInsert, $tblMicroprocesador);
//		$this->discoDuro($idInsert,$tblDiscoDuro);
//		$this->mainboard($idInsert,$tblMainboard);
//		$this->ram($idInsert,$tblRam);
//		$this->red($idInsert,$tblRed);
//		$this->software($idInsert,$tblSoft);
//		$this->otrosComponentes($idInsert,$tblOtro);
		$connection->commit();
			} catch (\Exception $e) {
				echo $e;
			}
		return $datos;
	}
	public function fichaTecnica($numero,$fecha,$nompc,$observacion,$id_user, $id_respfuncionario,$id_resppatrimonio) {
		$insert = $this->dbAdapter->
				createStatement(
				"INSERT INTO ficha_tecnica(numero, fecha,nompc,observacion,id_user, id_respfuncionario, id_resppatrimonio) "
						. "VALUES ($numero, '$fecha','$nompc', '$observacion',$id_user, $id_respfuncionario, $id_resppatrimonio)");
		 $insert->execute();
		 $datos =$this->dbAdapter->getDriver()->getConnection()->getLastGeneratedValue('ficha_tecnica_id_ficha_tecnica_seq');
		return $datos;
	}
	
	public function microprocesador($idInsert,$tblMicroprocesador) {
		
		$insert = $this->dbAdapter->createStatement(
				"INSERT INTO ft_compinternos(estructura, id_ficha_tecnica, id_disp_mar_mod)"
				. "VALUES ('".$tblMicroprocesador['estructura']."', '$idInsert',".$tblMicroprocesador['idMicroprocesador'].")");
		 $insert->execute();
		 
		return $insert;
	}
	
	public function discoDuro($idInsert,$tblDiscoDuro) {
		$insert = $this->dbAdapter->
				createStatement(
				"INSERT INTO ficha_tecnica(numero, fecha,nompc,observacion,id_user, id_respfuncionario, id_resppatrimonio) "
						. "VALUES ($numero, '$fecha','$nompc', '$observacion',$id_user, $id_respfuncionario, $id_resppatrimonio)");
		 $insert->execute();
		 $datos =$this->dbAdapter->getDriver()->getConnection()->getLastGeneratedValue('ficha_tecnica_id_ficha_tecnica_seq');
		return $datos;
	}
	
		public function mainboard($idInsert,$tblMainboard) {
		$insert = $this->dbAdapter->
				createStatement(
				"INSERT INTO ficha_tecnica(numero, fecha,nompc,observacion,id_user, id_respfuncionario, id_resppatrimonio) "
						. "VALUES ($numero, '$fecha','$nompc', '$observacion',$id_user, $id_respfuncionario, $id_resppatrimonio)");
		 $insert->execute();
		 $datos =$this->dbAdapter->getDriver()->getConnection()->getLastGeneratedValue('ficha_tecnica_id_ficha_tecnica_seq');
		return $datos;
	}
	
		public function ram($idInsert,$tblRam) {
		$insert = $this->dbAdapter->
				createStatement(
				"INSERT INTO ficha_tecnica(numero, fecha,nompc,observacion,id_user, id_respfuncionario, id_resppatrimonio) "
						. "VALUES ($numero, '$fecha','$nompc', '$observacion',$id_user, $id_respfuncionario, $id_resppatrimonio)");
		 $insert->execute();
		 $datos =$this->dbAdapter->getDriver()->getConnection()->getLastGeneratedValue('ficha_tecnica_id_ficha_tecnica_seq');
		return $datos;
	}
	
	
		public function red($idInsert,$tblRed) {
		$insert = $this->dbAdapter->
				createStatement(
				"INSERT INTO ficha_tecnica(numero, fecha,nompc,observacion,id_user, id_respfuncionario, id_resppatrimonio) "
						. "VALUES ($numero, '$fecha','$nompc', '$observacion',$id_user, $id_respfuncionario, $id_resppatrimonio)");
		 $insert->execute();
		 $datos =$this->dbAdapter->getDriver()->getConnection()->getLastGeneratedValue('ficha_tecnica_id_ficha_tecnica_seq');
		return $datos;
	}
	
		public function software($idInsert,$tblSoft) {
		$insert = $this->dbAdapter->
				createStatement(
				"INSERT INTO ficha_tecnica(numero, fecha,nompc,observacion,id_user, id_respfuncionario, id_resppatrimonio) "
						. "VALUES ($numero, '$fecha','$nompc', '$observacion',$id_user, $id_respfuncionario, $id_resppatrimonio)");
		 $insert->execute();
		 $datos =$this->dbAdapter->getDriver()->getConnection()->getLastGeneratedValue('ficha_tecnica_id_ficha_tecnica_seq');
		return $datos;
	}
	
	public function otrosComponentes($idInsert,$tblOtro) {
		$insert = $this->dbAdapter->
				createStatement(
				"INSERT INTO ficha_tecnica(numero, fecha,nompc,observacion,id_user, id_respfuncionario, id_resppatrimonio) "
						. "VALUES ($numero, '$fecha','$nompc', '$observacion',$id_user, $id_respfuncionario, $id_resppatrimonio)");
		 $insert->execute();
		 $datos =$this->dbAdapter->getDriver()->getConnection()->getLastGeneratedValue('ficha_tecnica_id_ficha_tecnica_seq');
		return $datos;
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
