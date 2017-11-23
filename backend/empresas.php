<?php
/*
*Nombre: Sergio Alberto Hernandez Méndez
*Fecha creacion: 26/10/17
*Descripcion de la pantalla: Llamadas que obtienen las empresas o actualizan las empresas aunadas a una tienda

*IMPORTANTE: 
*REVISAR COMO SE OBTENDRA AUTENTICACION $_SERVER['PHP_AUTH_PW']
*FALTA PROBAR CON PETICIONES REALES
*/


	REQUIRE_ONCE("./sqlConnections.php");
	REQUIRE_ONCE("./funcionesCRUD.php");

	
	

	//OBJETIVO: Obtiene las empresas que tienen cuenta con una tienda
	//PARAMETROS: idTienda
	//REGRESO: JSON con las empresas. El arreglo en "root" contiene id, nombre, contacto, color (hexa), y un string/json de datos
	function getEmpresas($idTienda){
		$directorioImagen = "http://165.227.99.16/PorMiTiendaApp/backend/imagenes/";

		$queryEmpresa = "SELECT e.idEmpresa AS id, e.nombreEmpresa AS nombre, e.contactoEmpresa AS contacto, e.colorEmpresa AS color, e.imagenEmpresa AS urlImagen, e.datosEmpresa AS datos FROM cuenta c, empresa e WHERE e.idEmpresa = c.idEmpresa AND c.idTienda = " . $idTienda;
		$empresasRst = executeQuery($queryEmpresa);

		if ($empresasRst['records']==0) {
			$empresasRst['success']=false;
			$empresasRst['msg'] = "Error getEmpresas: Informacion de empresas no valida";
			echo json_encode($empresasRst);
			return;
		}

		for ($i=0; $i < $empresasRst["records"]; $i++) { 
			$empresasRst["root"][$i]["urlImagen"] = $directorioImagen . $empresasRst["root"][$i]["urlImagen"];
		}

		echo json_encode($empresasRst);
	}

	





?>