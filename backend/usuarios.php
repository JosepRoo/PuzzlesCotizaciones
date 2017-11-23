<?php
	require ("sqlConnections.php");
	session_start();
	switch($_POST['action']){
		case "logeo":
			logeo();
			break;
		case "preRegistro":
			preRegistro();
			break;
		case "registro":
			registro();
			break;
		default:
			echo '';
			break;
	}
	//Valida si el usaurio existe y si la contraseña es correcta a la hora del LogIn
	function logeo() {
		$usuario = json_decode($_POST['usuario']);
		$query = "SELECT * from Usuario where correo='".$usuario->correo."'";
		$queryRst = executeQuery($query);
		if ($queryRst['records'] == 0 || $queryRst['root'][0]['contrasenia'] != md5($usuario->contrasenia)){
			$queryRst['root'] = NULL;
			$queryRst['success'] = false;
    	$queryRst['messageText'] = "Correo o contraseña incorrectos";
		}
		if (isset($_SESSION['error_sql']) && $_SESSION['error_sql'] != "") {
      $queryRst['success'] = false;
      $queryRst['messageText'] = $_SESSION['error_sql'];
    }
		confirm($queryRst['success']);
		echo json_encode($queryRst);
	}

?>