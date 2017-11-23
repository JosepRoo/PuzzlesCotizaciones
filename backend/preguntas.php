<?php
/*
*Nombre: Sergio Alberto Hernandez Méndez
*Fecha creacion: 26/10/17
*Descripcion de la pantalla: Llamadas que hacen la aplicacion para mostrar o contestar las preguntas del contenido

*IMPORTANTE: 
*REVISAR COMO SE OBTENDRA AUTENTICACION $_SERVER['PHP_AUTH_PW']
*FALTA PROBAR CON PETICIONES REALES
*/


    REQUIRE_ONCE("./sqlConnections.php");
    REQUIRE_ONCE("./funcionesCRUD.php");


    //OBJETIVO: Obtiene las preguntas de un idContenido
    //PARAMETROS: idContenido
    //REGRESO: JSON con las preguntas. El arreglo en "root" contiene id, pregunta y tipo
    function getPreguntas($idContenido){
        $queryPregunta = "SELECT idPregunta AS id, preguntaPregunta AS pregunta, tipoPregunta AS tipo FROM pregunta WHERE idContenido = " . $idContenido;
        $contenidoRst = executeQuery($queryPregunta);


        if ($contenidoRst['records']==0) {
            $contenidoRst['success']=false;
            $contenidoRst['msg'] = "Error getPreguntas: Informacion de preguntas no valida";
            echo json_encode($contenidoRst);
            return;
        }

        echo json_encode($contenidoRst);
    }





?>