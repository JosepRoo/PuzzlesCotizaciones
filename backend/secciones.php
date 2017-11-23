<?php
/*
*Nombre: Sergio Alberto Hernandez Méndez
*Fecha creacion: 21/10/17
*Descripcion de la pantalla: Llamadas que hacen la aplicacion para mostrarse en las secciones (capacitación, promoción, encuesta y mensaje)

*IMPORTANTE: 
*REVISAR COMO SE OBTENDRA AUTENTICACION $_SERVER['PHP_AUTH_PW']
*FALTA PROBAR CON PETICIONES REALES
*/


	REQUIRE_ONCE("./sqlConnections.php");
	REQUIRE_ONCE("./funcionesCRUD.php");


	// OBJETIVO: Obtiene la cantidad de contenido no visto por un usuario. 
	// PARAMETROS: No requiere parametros pero debe de tener las autorizaciones de usuario y contrasenia en la variable $_SERVER
	// REGRESO: El objeto que regresa será un resultSet con el nombre de la seccion y la cuenta del contenido no visto 
	function getNotificaciones($idTienda){ //Regresa numero de notificacion que tiene que tener cada empresa
		//Obtener todos los idContenido no visto con idTienda

		$queryContenido = "SELECT tc.tipoContenidoNombre, em.idEmpresa, COUNT(tc.tipoContenidoNombre) FROM contenido c, validacion v, tipoContenidoCtlg tc, empleado em, campania ca WHERE ca.idEmpleado = em.idEmpleado AND c.idCampania = ca.idCampania AND c.idContenido = v.idContenido AND tc.idTipoContenido = c.tipoContenido AND v.validacion = 1  AND v.idTienda = " . $idTienda . " GROUP BY tc.tipoContenidoNombre, em.idEmpresa";
		$contenidoNotifRst = executeQuery($queryContenido);

		if ($contenidoNotifRst['records']==0) {
			$contenidoNotifRst['msg'] = "Error getNotificaciones: Informacion de contenido no valida";
			echo json_encode($contenidoNotifRst);
			return;
		}

		echo json_encode($contenidoNotifRst);
	}

	//OBJETIVO: Obtiene el contenido de la sección
	//PARAMETROS: El id de la Empresa y el Tipo de Contenido que desea mostrarse
	//REGRESO: JSON con el contenido. El arreglo en "root" contiene id, pregunta y tipo
	function getInformacionSeccion($idTienda, $idEmpresa, $tipoContenido){
		//Obtener todos los tipos de contenido disponibles para cierta tienda

		$queryContenido = "SELECT c.idContenido AS id, c.tituloContenido AS titulo, c.assetContenido AS asset, c.puntosContenido AS puntos FROM empleado pl, contenido c, validacion v, campania ca WHERE pl.idEmpleado = c.idEmpleado AND v.idContenido = c.idContenido AND ca.idCampania = c.idCampania AND ca.estatusAprobacionCampania = 1 AND CURDATE() BETWEEN c.fechaInicioContenido AND c.fechaFinalContenido AND pl.idEmpresa = " . $idEmpresa . " AND c.tipoContenido = " . $tipoContenido . " AND v.idTienda = " . $idTienda ;
		$contenidoRst = executeQuery($queryContenido);


		if ($contenidoRst['records']==0) {
			$contenidoRst['success']=false;
			$contenidoRst['msg'] = "Error getInformacionSeccion: Informacion de contenido no valida";
			echo json_encode($contenidoRst);
			return;
		}

		echo json_encode($contenidoRst);
	}


	//OBJETIVO: Cambiar el estado de un contenido 
	//PARAMETROS: idContenido y idTienda y estado al que se
	//REGRESO: El objeto que regresa será un resultSet con el nombre de la seccion y jsons de este
	function setContenidoValidacion($idTienda, $idContenido, $estado){
		// $queryActualizaContenido = "UPDATE validacion SET validacion = 2 WHERE idTienda = " . $idTienda . " AND idContenido = " . $idContenido; //2 es visto
		// $actualizaContenidoRst = executeQuery($queryContenido);
		switch ($estado) {
			case 'contestado':
				$estadoCod = 3;
				break;
			case 'visto':
				$estadoCod = 2;
				break;
			default: 
				$estadoCod = 1;
				break;
		}


		$valoresJson = '{"validacion":'.$estadoCod.'}';
		$condicionesJson = '{"idTienda":'.$idTienda.',"idContenido":'.$idContenido.'}';

		$actualizaContenidoRst = actualiza($valoresJson, $condicionesJson, "validacion");

		if (!$actualizaContenidoRst['success']) {
			$actualizaContenidoRst['success'] = false;
			$actualizaContenidoRst['msg'] = "Error setContenidoValidacion: Informacion de contenido no valida";
			echo json_encode($actualizaContenidoRst);
			return;
		}

		echo json_encode($actualizaContenidoRst);
	}


	////FUNCIONES AUXILIARES

	//OBJETIVO: Obtener el id de la tienda con el el usuario 
	//PARAMETROS: idUsuario en $_REQUEST
	//REGRESO: un id de la tienda
	function obtenIdTienda(){
		$idUsuario = $_REQUEST["idUsuario"];

		//Obtener idTienda con idUsuario
		$opcionesTienda = '{"idUsuario":"' . $idUsuario . '"}';
		$opcionesTiendaJson = json_encode($opcionesTienda);
		$tiendaRst = selecciona($opcionesTienda, "tienda");

		if ($tiendaRst['records']==0) {
			$tiendaRst['msg'] = "Error getNotificaciones: Informacion de tienda no valida.";
			return $tiendaRst;
		}

		$idTienda = $tiendaRst["root"][0]["idTienda"];
		return $idTienda;

	}



?>