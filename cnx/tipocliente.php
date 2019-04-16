 <?php 
 	include_once("cnx/cnx_mysql.php");

 	
 	
 	function MostrarTipoCliente() {
	 	
	 	$cliente = array();

	 	$sql = "select cliente from tipocliente where estatus=1";
	    $result = mysqli_query($GLOBALS["db"], $sql);

	    if (mysqli_num_rows($result) > 0) { 
	    
	        while($row = mysqli_fetch_assoc($result)) {

	        	array_push($cliente, $row["cliente"]);
	        }
	                      
	    } else {

	    	return $cliente;
	    }

    	return $cliente;

    }



    function MostrarVendedores() {
	 	
	 	$Agente = array();

	 	$sql = "select iniciales from agentes where estatus=1 and tipo=1";
	    $result = mysqli_query($GLOBALS["db"], $sql);

	    if (mysqli_num_rows($result) > 0) { 
	    
	        while($row = mysqli_fetch_assoc($result)) {

	        	array_push($Agente, $row["iniciales"]);
	        }
	                      
	    } else {

	    	return $Agente;
	    }

    	return $Agente;

    }


    function MostrarGanancia($TipoCliente) {

    	$Ganancia = 0;
    	$sql = "select ganancia from tipocliente where estatus=1 and cliente='$TipoCliente';";

    	$result = mysqli_query($GLOBALS["db"], $sql);

    	if (mysqli_num_rows($result) > 0) { 
    		 
    		 $row = mysqli_fetch_assoc($result);
    		 $Ganancia = $row["ganancia"];

    	}

    	return $Ganancia;
     }


 