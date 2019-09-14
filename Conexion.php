<?php
class ConexionPGSQL{

      //declaración de variables
      public $host; // para conectarnos a localhost o el ip del servidor de postgres
      public $db; // seleccionar la base de datos que vamos a utilizar
      public $user; // seleccionar el usuario con el que nos vamos a conectar
      public $pass; // la clave del usuario
      public $conexion;  //donde se guardara la conexión
      public $url; //dirección de la conexión que se usara para destruirla mas adelante

      //creación del constructor
      function __construct(){
      }

      //creación de la función para cargar los valores de la conexión.
      public function cargarValores(){
              $this->host='localhost';
              $this->db='bayes';
              $this->user='postgres';
              $this->pass='123456';
              $this->conexion="host='$this->host' dbname='$this->db' user='$this->user' password='$this->pass' ";
              }

              //función que se utilizara al momento de hacer la instancia de la clase
              function conectar(){
                      $this->cargarValores();
                      $this->url=pg_connect($this->conexion);
                      return true;
              }

              function consultar($query){
                  	$resultado = pg_query($query) or die("Error en la Consulta SQL");
                  	return $resultado;
              }

              function getOptions($param){
                    $query = "select distinct ".$param." from infracciones_transito order by ".$param;
                  	$resultado = pg_query($query) or die("Error en la Consulta SQL");
                  	return $resultado;
              }

              function getCountViolationType($param){
                    $query = "select count(*)  from infracciones_transito where violation_type = '".$param."'";
                    $countParam = pg_query($query) or die("Error en la Consulta SQL");
                    $countParam = pg_fetch_row($countParam);
                    return $countParam[0];

              }

              function getCountParameterType($campo, $valor, $violationType){
                    $query = "select count(*)  from infracciones_transito i where i.".$campo." = '$valor' and i.violation_type = '".$violationType."'";
                    $countParam = pg_query($query) or die("Error en la Consulta SQL");
                    $countParam = pg_fetch_row($countParam);
                    return $countParam[0];
              }

              function getCountInfractions(){
                    $query = "select count(*)  from infracciones_transito";
                    $countParam = pg_query($query) or die("Error en la Consulta SQL");
                    $countParam = pg_fetch_row($countParam);
                    return $countParam[0];
              }

              //función para destruir la conexión.
              function destruir(){
                      pg_close($this->url);
              }

      }

?>
