<?php

namespace Rangoip\Model\Entity;

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Adapter\Adapter;
use Zend\Db\Sql\Sql;
use Zend\Db\Sql\Select;
use Zend\Db\ResultSet\ResultSet;

class Rangoip extends TableGateway {

	private $dbAdapter;

	public function __construct(Adapter $adapter = null, $databaseSchema = null, ResultSet $selectResultPrototype = null) {
		$this->dbAdapter = $adapter;
		return parent::__construct('Rangoip', $this->dbAdapter, $databaseSchema, $selectResultPrototype);
	}

	public function insertar($rangoinicial,$rangofinal,$id_area) {
		$insert = $this->dbAdapter->
				createStatement(
				"INSERT INTO rango_ip (rango_inicial,rango_final,id_area) "
						. "VALUES ('$rangoinicial','$rangofinal',$id_area)");
		$datos = $insert->execute();
		return $datos;
	}
	public function modificar($id, $rangoinicial,$rangofinal,$id_area) {
		$update = $this->dbAdapter->
				createStatement(
				"update rango_ip set rango_inicial=trim('$rangoinicial'),rango_final=trim('$rangofinal'),id_area=$id_area"
						. "where id_rango=$id");
		$datos = $update->execute();
		return $update;
	}
	
//ordenar descendente 	
//  order by vigencia desc , decripcion asc

	public function lista() {
		//$consulta = $this->dbAdapter->query("SELECT id_estado,numero,descripcion, vigencia FROM estado order by descripcion asc", Adapter::QUERY_MODE_EXECUTE);


		$consulta = $this->dbAdapter->query("SELECT r.id_rango, r.rango_inicial, r.rango_final, a.id_area, r.vigencia, a.descripcion as area FROM rango_ip r inner join area a on r.id_area=a.id_area", Adapter::QUERY_MODE_EXECUTE);
		$datos = $consulta->toArray();
		return $datos;
	}

	public function buscar($id){
        $consulta=$this->dbAdapter->query("SELECT id_rango, rango_inicial, rango_final, a.id_area, r.vigencia, a.descripcion as area FROM rango_ip r inner join area a on r.id_area=a.id_area where r.id_rango=$id",Adapter::QUERY_MODE_EXECUTE);
        $datos=$consulta->toArray();
        return $datos[0];
    }
	  public function buscarRango($rangoinicial){
        $consulta=$this->dbAdapter->query("SELECT id_rango as value,rango_inicial as rangoinicial, rango_final as rangofinal, vigencia FROM rango_ip where rango_inicial like '%$rangoinicial%'",Adapter::QUERY_MODE_EXECUTE);
        $datos=$consulta->toArray();        
        return $datos;    
    }

/*

public function eliminar($id) {
		$delete = $this->dbAdapter->
				createStatement(
				"UPDATE estado set vigencia=FALSE where id_estado=$id");
		$datos = $delete->execute();
		return $delete;
	}
*/
   
	public function eliminar($id,$vigencia) {
		$delete = $this->dbAdapter->
				createStatement("UPDATE rango_ip SET vigencia=$vigencia WHERE id_rango=$id");
		$datos = $delete->execute();
		return $datos;


	}
	
	//MARCA MODELO ??
	/*
	public function buscarEstadoModelo($descripcion){
        $consulta=$this->dbAdapter->query(
				"SELECT id_estado as value,numero as cantidad, descripcion as label, vigencia FROM estado where descripcion like '%$descripcion%'",Adapter::QUERY_MODE_EXECUTE);
        $datos=$consulta->toArray();        
        return $datos;    
    }
    */
}














