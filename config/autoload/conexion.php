<?php
/** 
 * Clase de conexion con la base de datos
 * 
 * */
 class Conexion{
  private $host;
  private $user;
  private $password;
  private $db;
  private $port=5432; // Por defecto es 5432
  private $con=null;
  public function Conexion($host="172.16.0.152",$user="user_geresa",$password="user_geresa2017", $db="siganew",$port=5432){
   $this->host=$host;
   $this->user=$user;
   $this->password=$password;
   $this->db=$db;
   $this->port=$port;
   $this->con = pg_pconnect("host=".$this->host." port=".$this->port." dbname=".$this->db." user=".$this->user." password=".$this->password);
   if (!$this->con) die("Ocurrio un error al intentar la conexion");
 }//function conexion
 public function getConexion(){
  return $this->con;
  }
 } //class
?> 