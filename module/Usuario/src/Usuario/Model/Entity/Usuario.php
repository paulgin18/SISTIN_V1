<?php
namespace Usuario\Model\Entity;
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Adapter\Adapter;
use Zend\Db\Sql\Sql;
use Zend\Db\Sql\Select;
use Zend\Crypt\Password\Bcrypt;

use Zend\Db\ResultSet\ResultSet;

class Usuario extends TableGateway {

	private $dbAdapter;

	public function __construct(Adapter $adapter = null, $databaseSchema = null, ResultSet $selectResultPrototype = null) {
		$this->dbAdapter = $adapter;
		return parent::__construct('Usuario', $this->dbAdapter, $databaseSchema, $selectResultPrototype);
	}

	public function insertar($usuario, $password,$id_personal, $id_rol, $id_unidad_ejecutora) {
		$bcrypt = new Bcrypt();
		$securePass = $bcrypt->create($password);

		$insert = $this->dbAdapter->
				createStatement(
				"INSERT INTO usuario (usuario, password,id_personal,id_rol,id_unidad_ejecutora) "
						. "VALUES "
						. "(upper(trim('$usuario')),$securePass,id_personal,id_rol,id_unidad_ejecutora)");
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

	public function login($usuario,$pass){
		try{
        $consulta=$this->dbAdapter->query(
			"SELECT id_user, usuario, password, vigencia, fecha_registro, fecha_actualizacion,"
			. " id_personal, id_rol rol, id_unidad_ejecutora dependencia from usuario where usuario='$usuario'",
				Adapter::QUERY_MODE_EXECUTE);
        $datos=$consulta->toArray();
		count($datos)=='0'?$consulta=array('usuario'=>null):$consulta=$datos[0];
        return $consulta;
		} catch (\Exception $e) {
			echo "ERROR".$e;
		}
    } 
	
	public function eliminar($id,$vigencia){
		$insert = $this->dbAdapter->createStatement("update anio set vigencia=$vigencia where id_anio=$id");
		$datos = $insert->execute();
		return $datos;
    } 
}