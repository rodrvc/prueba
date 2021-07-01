<?php
class MySQL{
  private $conexion; private $total_consultas;
  public function MySQL(){
    if(!isset($this->conexion)){
 /*     $this->conexion = (mysql_connect("localhost","skechile_usuario","Andain.,1220-"))
        or die(mysql_error());
      mysql_select_db("skechile_ecommerce",$this->conexion) or die(mysql_error()); 
      */

    $this->conexion = (mysql_connect("skechers.clgbenru2ejx.us-east-1.rds.amazonaws.com","skechers_admin","ahshiex3Oth"))
        or die(mysql_error());
      mysql_select_db("skechile_ecommerce",$this->conexion) or die(mysql_error());


    }
  }
  public function consulta($consulta){
    $this->total_consultas++;
    $resultado = mysql_query($consulta,$this->conexion);
    if(!$resultado){
      echo 'MySQL Error: ' . mysql_error();
      exit;
    }
    return $resultado;
  }
  public function fetch_array($consulta){
   return mysql_fetch_array($consulta);
  }
  public function num_rows($consulta){
   return mysql_num_rows($consulta);
  }
  public function getTotalConsultas(){
   return $this->total_consultas;
  }
  public function ejecutar($sql)
  {
    $result =mysql_query($sql, $this->conexion);
    return mysql_affected_rows();
  }
}
?>