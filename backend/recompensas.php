<?php
/*
*Nombre: Sergio Alberto Hernandez Méndez
*Fecha creacion: 03/11/17
*Descripcion de la pantalla: Llamadas que obtienen o actualizan los puntos de un usuario

*IMPORTANTE: 
*REVISAR COMO SE OBTENDRA AUTENTICACION $_SERVER['PHP_AUTH_PW']
*FALTA PROBAR CON PETICIONES REALES
*/


    REQUIRE_ONCE("./sqlConnections.php");
    REQUIRE_ONCE("./funcionesCRUD.php");


    //OBJETIVO: Obtiene las puntos maximos del mes, los acumulados al mes, el total de puntos acumulados y redimidos la fecha de la rifa, y la imagen
    //PARAMETROS: idTienda
    //REGRESO: JSON con totalPuntos, totalPuntosMes, maximosPuntosMes, puntosRedimidos
    function getRecompensas($idTienda){
        $queryTotalPuntos = "SELECT SUM(puntos) AS totalPuntos FROM historialPuntos WHERE idTienda = " . $idTienda;
        $totalPuntosRst = executeQuery($queryTotalPuntos);

        if($totalPuntosRst['root'][0]['totalPuntos']==null){
            $totalPuntos=0;
        }else{
            $totalPuntos=$totalPuntosRst['root'][0]['totalPuntos'];
        }

        $queryTotalPuntosMes = "SELECT SUM(puntos) AS totalPuntosMes FROM historialPuntos WHERE MONTH(fecha)= MONTH(CURRENT_DATE) AND puntos > 0 AND idTienda = " . $idTienda;
        $totalPuntosMesRst = executeQuery($queryTotalPuntosMes);

        if($totalPuntosMesRst['root'][0]['totalPuntosMes']==null){
            $totalPuntosMes=0;
        }else{
            $totalPuntosMes=$totalPuntosMesRst['root'][0]['totalPuntosMes'];
        }

        //Puntos maximos al mes
        // $queryTotalPuntosMes = "SELECT SUM(puntos) AS totalPuntosMes FROM historialPuntos WHERE idTienda = " . $idTienda;
        // $maximosPuntosMesRst = executeQuery($queryTotalPuntosMes);

        // if($maximosPuntosMesRst['root'][0]['maximosPuntosMes']==null){
        //     $maximosPuntosMes=0;
        // }else{
        //     $maximosPuntosMes=$maximosPuntosMesRst['root'][0]['maximosPuntosMes'];
        // }


        $queryPuntosRedimidos = "SELECT SUM(puntos)*(-1) AS puntosRedimidos FROM historialPuntos WHERE puntos < 0 AND idTienda = " . $idTienda;
        $puntosRedimidosRst = executeQuery($queryPuntosRedimidos);

        if($puntosRedimidosRst['root'][0]['puntosRedimidos']==null){
            $puntosRedimidos=0;
        }else{
            $puntosRedimidos=$puntosRedimidosRst['root'][0]['puntosRedimidos'];
        }

        $queryRifa = "SELECT idRifa, fechaRifa, assetRifa FROM rifa WHERE fechaRifa > CURRENT_DATE ORDER BY fechaRifa LIMIT 1";
        $rifaRst = executeQuery($queryRifa);

        $idRifa = $rifaRst["root"][0]["idRifa"];
        $fechaRifa = $rifaRst["root"][0]["fechaRifa"];
        $assetRifa = $rifaRst["root"][0]["assetRifa"];

        $rootRst = array('totalPuntos' => $totalPuntos, 'totalPuntosMes' => $totalPuntosMes, 'maximosPuntosMes' => $maximosPuntosMes, 'puntosRedimidos' => $puntosRedimidos, 'idRifa' => $idRifa, 'fechaRifa' => $fechaRifa, 'assetRifa' => $assetRifa);

        $puntosRst = array('root' => $rootRst, 'success' => true, 'records' => 1);

        echo json_encode($puntosRst);
    }





?>