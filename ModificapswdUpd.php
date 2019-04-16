<?php 
	require_once 'cnx/ins.php';

	$Agente = (isset($_POST['Agente'])) ? $_POST['Agente'] : "";
	$pass = (isset($_POST['pass'])) ? $_POST['pass']  : "";
	$Clave = (isset($_POST['Clave'])) ? $_POST['Clave']  : "";


	$sql = "select iniciales, nombre, apellido from agentes where iniciales = '".$Agente."' and password = md5('".$Clave."') and estatus = 1";

    $result = mysqli_query($GLOBALS["db"], $sql);

  	$Existe = (mysqli_num_rows($result) > 0) ? true : false;



  	if ($Existe) {
		
		$Actualizar =  update('agentes',array('password'=>utf8_decode(md5($pass))), array('%s'),array('iniciales'=>$Agente,'estatus'=>'1'),array('%s','%s'));	
		$Mensaje = ($Actualizar) ? "Actualizado correctamente" : "No se pudo actualizar la contraseña. Favor de verificar";
		
  	}
  	else {
  		
  		$Actualizar = false;
  		$Mensaje = "No existe el usuario, o la contraseña es incorrecta." ;
  	}

  	


  	echo $Mensaje;
	
	 

?>

