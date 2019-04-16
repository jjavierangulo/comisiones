<?php
	
	/*$msconnect=mssql_connect("192.168.1.11","sa","B1Admin");  
	$msdb=mssql_select_db("PRUEBAS_08ENE16",$msconnect);  */


/*	$serverName = "192.168.1.11"; //serverName\instanceName
	$connectionInfo = array( "Database"=>"PRUEBAS_08ENE16", "UID"=>"sa", "PWD"=>"B1Admin");
	$conn = sqlsrv_connect( $serverName, $connectionInfo);

	if( $conn ) {
	     echo "Conexión establecida.<br />";
	}else{
	     echo "Conexión no se pudo establecer.<br />";
	     die( print_r( sqlsrv_errors(), true));
}*/



class conexion
{
 private static $instance = null;
        private $conn;


 	  /*private function __construct() {}
        private function __clone(){ }*/
        
        
        public static function getInstance()
         {
            if (!isset(self::$instance)) {
                $c = __CLASS__;
                self::$instance = new $c;
            }
            return self::$instance;
        }

       
        public function AbrirConexion() {
            try{
                if(!isset($this->conn)){
                ini_set('mssql.charset', 'UTF-8');
                $this->conn=mssql_connect("192.168.1.11","sa","B1Admin");
                //$res = mssql_select_db("PRUEBAS_13DIC16",$this->conn);
                $res = mssql_select_db("DUES_TEXTIL_PROD",$this->conn);
                }
                return $this->conn;
             }
             catch(Exception $ex)    {
          //    throw new $ex->getMessage();
              throw new Exception ("La conexión al servidor a fallado..");
             }
 		  }
    
        
        public function CerrarConexion(){
		  	if( isset( $this->conn) ){
			   mssql_close( $this->conn);
			   unset( $this->conn );
		  	}
		}
}

?>