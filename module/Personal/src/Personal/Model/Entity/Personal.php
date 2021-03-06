<?php

namespace Personal\Model\Entity;

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Adapter\Adapter;
use Zend\Db\Sql\Sql;
use Zend\Db\Sql\Select;
use Zend\Db\ResultSet\ResultSet;
use Zend\Session\Container;
use Zend\Json\Json;
use Zend\View\Model\JsonModel;

class Personal extends TableGateway {

	private $dbAdapter;

	public function __construct(Adapter $adapter = null, $databaseSchema = null, ResultSet $selectResultPrototype = null) {
		$this->dbAdapter = $adapter;
		return parent::__construct('Personal', $this->dbAdapter, $databaseSchema, $selectResultPrototype);
	}

	public function insertar($nombre,$apellido_paterno,$apellido_materno,$dni,$id_area,$responsable) {
		$insert = $this->dbAdapter->
		createStatement("INSERT INTO personal (nombre,apellido_paterno,apellido_materno,dni,id_area,respatrimonial) VALUES (upper(trim('$nombre')),upper(trim('$apellido_paterno')),upper(trim('$apellido_materno')),$dni,$id_area,$responsable)");
		$datos = $insert->execute();
		return $datos;
	}
	public function modificar($id, $nombre,$apellido_paterno,$apellido_materno,$dni,$id_area,$responsable) {
		$update = $this->dbAdapter->
				createStatement(
				"update personal set nombre=upper(trim('$nombre')),apellido_paterno=upper(trim('$apellido_paterno')),apellido_materno=upper(trim('$apellido_materno')) ,dni=$dni,respatrimonial=$responsable, id_area=$id_area"
						. "where id_personal=$id");
		$datos = $update->execute();
		return $datos;
	}
	
	public function lista() {
		$consulta = $this->dbAdapter->query(
				"SELECT p.id_personal, p.nombre, p.apellido_paterno,p.apellido_materno, p.dni, p.id_area, p.vigencia, respatrimonial,a.descripcion FROM personal p inner join area a on p.id_area=a.id_area "
				. " order by a.descripcion asc, respatrimonial desc, apellido_paterno asc", Adapter::QUERY_MODE_EXECUTE);
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

        $consulta=$this->dbAdapter->query("SELECT p.id_personal, p.nombre, p.apellido_paterno,p.apellido_materno, p.dni, p.id_area, p.vigencia, a.descripcion,respatrimonial FROM personal p inner join area a on p.id_area=a.id_area where p.id_personal=$id",Adapter::QUERY_MODE_EXECUTE);
        $datos=$consulta->toArray();
        return $datos[0];
    }
    public function buscarPersonalsiga($descripcion){
//require "cnn.php";
$con=null;
//$obcon= new cnn("172.16.0.152","user_geresa","user_geresa2017","siganew",5432);
   $con = pg_pconnect("host='172.16.0.152' port='5432' dbname='siganew' user='user_geresa' password='user_geresa2017'");
$consulta = "select pers_id as value,(pers_apellpaterno || ' '|| pers_apellmaterno ||' '|| pers_nombres) as label from remoto.view_personas "
		. "where (pers_apellpaterno || ' '|| pers_apellmaterno ||' '|| pers_nombres) like Upper('%$descripcion%')";
$result = pg_query($con, $consulta);


        return $result;
    }

    public function buscarPersonal($descripcion){
        $consulta=$this->dbAdapter->query(
		"SELECT id_personal as value, (apellido_paterno ||' '||apellido_materno ||' ' || nombre) as label, vigencia FROM personal where (apellido_paterno ||' '||apellido_materno ||' ' || nombre) like Upper('%$descripcion%')",Adapter::QUERY_MODE_EXECUTE);
        $datos=$consulta->toArray();        
        return $datos;    
    }

	public function buscarFuncionario($descripcion, $unidad_ejecutora){
        $consulta=$this->dbAdapter->query(
		"Select id_personal as value,(apellido_paterno ||' '||apellido_materno ||' ' || nombre) as label, fu_brespatrimonial(fu_bunidadorganica(p.id_area),p.id_area)respatrimonial,a.id_area, a.descripcion area,"
				. " a.id_uni_org,(select u.descripcion unidad_organica from area ar "
				. " inner join unidad_organica u on ar.id_uni_org=u.id_uni_org where ar.id_area=a.id_area)  from personal p "
				. " inner join area a on p.id_area=p.id_area "
				. " where (apellido_paterno ||' '||apellido_materno ||' ' || nombre) like upper(trim('%$descripcion%')) "
				. " and a.id_uni_org=(select id_uni_org from unidad_organica uor inner join unidad_ejecutora ue on uor.id_unidad_ejecutora=ue.id_unidad_ejecutora "
				. " where ue.id_unidad_ejecutora=$unidad_ejecutora and id_uni_org=fu_bunidadorganica(p.id_area))",Adapter::QUERY_MODE_EXECUTE);
        $datos=$consulta->toArray();        
        return $datos;    
    }

	
	
	public function buscarPersonalModelo($descripcion){
        $consulta=$this->dbAdapter->query(
				"SELECT id_personal as value, (apellido_paterno ||' '||apellido_materno ||' ' || nombre) as label, vigencia FROM personal where (apellido_paterno ||' '||apellido_materno ||' ' || nombre) like '%$descripcion%'",Adapter::QUERY_MODE_EXECUTE);
        $datos=$consulta->toArray();        
        return $datos;    
    }
	public function eliminar($id,$vigencia){
		$insert = $this->dbAdapter->createStatement("update personal set vigencia=$vigencia where id_personal=$id");
		$datos = $insert->execute();
		return $datos;
    } 
	
	public function lista_personal() {
		$datos = array();
		$datos1 = array();
		$datos2 = array();
		$combined = array();
		$array_vacio = array('conex' => '' , 'id_personal' => '', 'nombres' => '' );
		$consulta = $this->dbAdapter->query("select 'L' as conex, id_personal, (COALESCE(apellido_paterno,'') || ' ' || COALESCE(apellido_materno,'') || ' ' || COALESCE(nombre,'')) as nombres 
from personal order by (COALESCE(apellido_paterno,'') || ' ' || COALESCE(apellido_materno,'') || ' ' || COALESCE(nombre,'')) asc", Adapter::QUERY_MODE_EXECUTE);
		$datos = $consulta->toArray();
		error_reporting(E_ERROR | E_PARSE);
		try {
			$con=null;
			//$obcon= new cnn("172.16.0.152","user_geresa","user_geresa2017","siganew",5432);
			   $con = pg_pconnect("host='172.16.0.152' port='5432' dbname='siganew' user='user_geresa' password='user_geresa2017' connect_timeout=10");
			$consulta1 = "select 'R' as conex, pers_id as id_personal,
pers_apellpaterno || ' '|| pers_apellmaterno ||' '|| pers_nombres as nombres 
from remoto.view_personas 
order by pers_apellpaterno || ' '|| pers_apellmaterno ||' '|| pers_nombres " ;
			if ($con) {

				$datos2 = pg_query($con, $consulta1);
				$datos1 = pg_fetch_all($datos2);
				//var_dump($datos1);
			}else{
				//var_dump($datos1);
				$datos1 = array($array_vacio);
			}
		} catch (\Exception $e) {
	
		}
        $combined = array_merge($datos, $datos1);
        return $combined;
	}
}
