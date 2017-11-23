<?php
/*
*Nombre: Sergio Alberto Hernandez Méndez
*Fecha creacion: 5/11/17
*Descripcion de la pantalla: Llamadas que crean y manipulan los grupos de tiendas de una empresa

*IMPORTANTE: 
*REVISAR COMO SE OBTENDRA AUTENTICACION $_SERVER['PHP_AUTH_PW']
*FALTA PROBAR CON PETICIONES REALES
*/


    REQUIRE_ONCE("./sqlConnections.php");
    REQUIRE_ONCE("./funcionesCRUD.php");


    //OBJETIVO: Crea los grupos 
    //REGRESO: JSON con la cantidad de tiendas e info del grupo
    function crearGrupo($arrPostParams){
        $idEmpresa = $arrPostParams["idEmpresa"];
        $nombreGrupo = $arrPostParams["nombreGrupo"];
        $parametros = json_encode($arrPostParams["parametros"]);
        $tipoParametros = $arrPostParams["tipoParametros"];

        $valoresJson = json_encode(array('idEmpresa' => $idEmpresa, 'nombreGrupo' => $nombreGrupo, 'filtrosGrupo' => $parametros, 'tipoFiltroGrupo' => $tipoParametros));
        $insertaRst = inserta($valoresJson, "grupo");

        if (!$insertaRst['id']||!$insertaRst['success']) {
            $insertaRst['success'] = false;
            $insertaRst['msg'] = "Error crearGrupo: Informacion de crearGrupo no valida";
            echo json_encode($insertaRst);
            return;
        }


         switch ($tipoParametros) {
            case 1:// 1 = filtro de especificaciones (json)  checar que que las tiendas tengan los parametros de filtrosGrupo
                $queryChecarTiendas = selecciona($parametros, "tienda", ["idTienda"], false); //SELECT idTienda from tienda where parametro1 = ...
                $parametros = json_encode(array('idEmpresa' => $idEmpresa));
                $queryCuentasTiendas = selecciona($parametros, "cuenta", ["idTienda"], false);
                $tiendasRst = $queryChecarTiendas . " INTERSECT " . $queryCuentasTiendas;

                $idTiendasArr = array();
                foreach ($tiendasRst["root"] as $key => $reg) {
                    array_push($idTiendasArr, $reg["idTienda"]);
                }

                break;
            case 2:// 2 = filtro de numeroCuentas(arr)
                $numeroCuentasArr = json_decode($parametros, true);
                $numeroCuentasStr = "'" . implode("', '", $numeroCuentasArr) . "'";

                $queryTiendasStr = "SELECT idTienda FROM cuenta WHERE numeroCuenta IN (" . $numeroCuentasStr . ")";
                $tiendasRst = executeQuery($queryTiendasStr);

                $idTiendasArr = array();
                foreach ($tiendasRst["root"] as $key => $reg) {
                    array_push($idTiendasArr, $reg["idTienda"]);
                }
                break;
            case 3:// 3 = filtro de idTienda(arr)
                $idTiendasArr = json_decode($parametros, true);
                break;
            default:
                # code...
                break;
        }

        $insertaRst["tamanioGrupo"] = count($idTiendasArr);

        echo json_encode($insertaRst);
    }





?>