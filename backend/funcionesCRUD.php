<?php
/*
*Nombre: Sergio Alberto Hernandez Méndez
*Fecha creacion: 08/10/17
*Descripcion de la pantalla: SELECT/INSERT/UPDATE/DELETE de cualquier tabla

*IMPORTANTE: FALTA PROBAR CON PETICIONES REALES
*/


	REQUIRE_ONCE("./sqlConnections.php");


	// $tablaStr = $_REQUEST["tabla"];
	// $valoresJson = json_decode($_POST["valores"], true);
	// $condicionesJson = json_decode($_POST["condiciones"], true);


	// switch ($_REQUEST['accion']) {
 //        case 'seleccion': //Necesita recibir un JSON de condiciones por $_POST["condiciones"], la tabla por $_REQUEST["tabla"]
 //        	$jsonRst = selecciona($condicionesJson, $tabla);
	// 		break;
	// 	case 'insercion': //Necesita recibir un JSON de los valores a insertar por $_POST["valores"], la tabla por $_REQUEST["tabla"]
 //        	$jsonRst = inserta($valoresJson, $tablaStr);
	// 		break;
	// 	case 'actualizacion'://Necesita recibir un JSON de los valores a insertar por $_POST["valores"], un JSON de condiciones por $_POST["condiciones"], la tabla por $_REQUEST["tabla"]
 //        	$jsonRst = actualiza($valoresJson, $condicionesJson, $tablaStr);
	// 		break;
	// 	case 'eliminacion':	//Necesita recibir un JSON de condiciones por $_POST["condiciones"], la tabla por $_REQUEST["tabla"]
 //        	$jsonRst = elimina($condicionesJson, $tablaStr);
	// 		break;
 //        default:
 //            break;
 //    }

 //    if ($solicitud == "mostrar") {
 //        echo json_encode($jsonRst);
 //    }

    function selecciona($condicionesJson, $tablaStr, $columnas=array('*'), $ejecutarQuery = true){
        $columnasStr = " ";
        foreach ($columnas as $llave => $columna) {
            $columnasStr .= $columna . ", ";
        }
        $columnasStr = substr($columnasStr, 0, -2) . " ";


    	$queryStr = "SELECT " . $columnasStr . " FROM " . $tablaStr . " " . generaCondicion($condicionesJson);
        if (!$ejecutarQuery) {
            return $queryStr;
        }

    	$seleccionaRst = executeQuery($queryStr);
    	return $seleccionaRst;
    }

    function inserta($valoresJson, $tablaStr){
    	$columnasValoresArr = generaColumnasValoresInsercion($valoresJson, "inserta");

    	$queryStr = "INSERT INTO " . $tablaStr . " " . $columnasValoresArr["columnas"] . " VALUES " . $columnasValoresArr["valores"];

    	$insertaRst = executeQuery($queryStr);

    	return $insertaRst;
    }

    function actualiza($valoresJson, $condicionesJson, $tablaStr){
    	$columnasValoresArr = generaColumnasValoresInsercion($valoresJson, "actualiza");

    	$queryStr = "UPDATE " . $tablaStr . " SET " . $columnasValoresArr["columnas"] . " " . generaCondicion($condicionesJson);
    	$actualizaRst = executeQuery($queryStr);

    	return $actualizaRst;
    }

    function elimina($condicionesJson, $tablaStr){
    	$queryStr = "DELETE FROM " . $tablaStr . " " . generaCondicion($condicionesJson);
    	$deleteRst = executeQuery($queryStr);

    	return $deleteRst;
    }


    ////FUNCIONES AUXILIARES

    //OBJETIVO: Generar la condición WHERE para un query
    //PARAMETROS: Un objeto json que trae las condiciones como "columna":"valor"
    //REGRESO: Un string construido de la siguiente forma "WHERE columna1=valor1 AND columna2=valor2 AND..."
    function generaCondicion($json){
        $jsonDec = json_decode($json);
    	$whereStr = "";
    	foreach ($jsonDec as $key=>$value) {
    		$whereStr = conCatConditional($whereStr, $key . " = '" . $value . "' ");
    	}
    	return $whereStr;
    }

    //OBJETIVO: Generar los strings de columnas y valores para un query INSERT y UPDATE
    //PARAMETROS: 
    // 	Un objeto json que trae las condiciones como "columna":"valor". 
    // 	Un string del tipo de función que la invoca "inserta" o "actualiza"
    //REGRESO: Un arreglo con dos strings. 
    // 	El primer string está construido de la siguiente forma :
    //		Si $funcion es "inserta": "(columna1, columna2, columna3)"
    // 		Si $funcion es "actualiza": "columna1 = valor1, columna2 = valor2,..."
    // 	El segundo string está construido de la siguiente forma
    //		Si $funcion es "inserta": "(valor1, valor2, valor3)"
    // 		Si $funcion es "actualiza": ""
    function generaColumnasValoresInsercion($json, $funcion){
    	$columnasStr = "";
    	$valoresStr = "";

        $json = json_decode($json);

    	switch ($funcion) {
    		case "inserta":
    			$columnasStr .= "(";
    			$valoresStr .= "(";

		    	foreach ($json as $columna => $valor) {
		    		$columnasStr .= $columna . ", ";
		    		$valoresStr .= "'" . $valor . "', ";
		    	}

    			$columnasStr = substr($columnasStr, 0, -2);
		    	$valoresStr = substr($valoresStr, 0, -2);

		    	$columnasStr .= ")";
		    	$valoresStr .= ")";
		    	break;
    		case "actualiza":
		    	foreach ($json as $columna => $valor) {
		    		$columnasStr .= $columna . " = " . $valor . ", ";
		    	}
    			$columnasStr = substr($columnasStr, 0, -2);
		    	$valoresStr = substr($valoresStr, 0, -2);
    			break;
    		default:
    			break;
    	}

    	$columnasValoresArr = array("columnas" => $columnasStr, "valores" => $valoresStr);

    	return $columnasValoresArr;
    }

?>