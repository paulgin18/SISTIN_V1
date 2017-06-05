
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

		//chekear BD

		return parent::__construct('Rangoip', $this->dbAdapter, $databaseSchema, $selectResultPrototype);
	}

	public function insertar($rango_inicial,$rango_final) {
		$insert = $this->dbAdapter->
				createStatement(
				"INSERT INTO rango_ip (rango_inicial,rango_final)"
						. "VALUES ($rango_inicial,'$rango_final')");
		$datos = $insert->execute();
		return $datos;
	}
	public function modificar($id, $rango_inicial,$rango_final) {
		$update = $this->dbAdapter->
				createStatement(
				"update rango_ip set rango_inicial=$rango_inicial,rango_final=$rango_final"
						. "where id_rango=$id");
		$datos = $update->execute();
		return $update;
	}
	
//ordenar descendente 	
//  order by vigencia desc , decripcion asc

	public function lista() {
		//$consulta = $this->dbAdapter->query("SELECT id_estado,numero,descripcion, vigencia FROM estado order by descripcion asc", Adapter::QUERY_MODE_EXECUTE);


		$consulta = $this->dbAdapter->query("SELECT id_rango,rango_inicial,rango_final,vigencia FROM rango_ip ", Adapter::QUERY_MODE_EXECUTE);
		$datos = $consulta->toArray();
		return $datos;
	}

	public function buscar($id){
        $consulta=$this->dbAdapter->query("SELECT id_rango,rango_inicial,rango_final,vigencia FROM rango_ip where id_rango=$id",Adapter::QUERY_MODE_EXECUTE);
        $datos=$consulta->toArray();
        return $datos[0];
    }



	  public function buscarRangoip($rango_inicial){
        $consulta=$this->dbAdapter->query(
		"SELECT id_rango as value,rango_inicial as ipinicio, rango_final as ipfinal, vigencia FROM rango_ip where rango_inicial like '%$rango_inicial%'",Adapter::QUERY_MODE_EXECUTE);
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
				createStatement("UPDATE rango_ip set vigencia=$vigencia where id_rango=$id");
		$datos = $delete->execute();
		return $datos;
	}
	
	//MARCA MODELO ??
	//no pasa nada

	public function buscarEstadoModelo($descripcion){
        $consulta=$this->dbAdapter->query(
				"SELECT id_estado as value,numero as cantidad, descripcion as label, vigencia FROM estado where descripcion like '%$descripcion%'",Adapter::QUERY_MODE_EXECUTE);
        $datos=$consulta->toArray();        
        return $datos;    
    }
}














