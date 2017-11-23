<?php
/*
*Nombre: Sergio Alberto Hernandez Méndez
*Fecha creacion: 26/10/17
*Descripcion de la pantalla: Hacer registro

*IMPORTANTE: 
*REVISAR COMO SE OBTENDRA AUTENTICACION $_SERVER['PHP_AUTH_PW']
*FALTA PROBAR CON PETICIONES REALES
*/


    REQUIRE_ONCE("./sqlConnections.php");
    REQUIRE_ONCE("./funcionesCRUD.php");


    //OBJETIVO: Hace el preregistro del usuario
    //PARAMETROS: Se obtienen por post los parametros nombre, usuario y contrasenia
    //REGRESO: JSON con las empresas. El arreglo en "root" contiene id, nombre, contacto, color (hexa), y un string/json de datos
    function preregistro(){
        $valoresSeleccionJson = json_encode(array('nombreUsuario' => $_POST["nombre"]));
        $seleccionaRst = selecciona($valoresSeleccionJson, "usuario");

        if ($seleccionaRst["records"]>0) {
            $seleccionaRst['success']=false;
            $seleccionaRst['root']=null;
            $seleccionaRst['msg'] = "Error preregistro: Usuario ya registrado";
            echo json_encode($seleccionaRst);
            return;
        }

        $valoresSeleccionJson = json_encode(array('correoUsuario' => $_POST["correo"]));
        $seleccionaRst = selecciona($valoresSeleccionJson, "usuario");

        if ($seleccionaRst["records"]>0) {
            $seleccionaRst['success']=false;
            $seleccionaRst['root']=null;
            $seleccionaRst['msg'] = "Error preregistro: Correo ya registrado";
            echo json_encode($seleccionaRst);
            return;
        }

        $valoresJson = json_encode(array('nombreUsuario' => $_POST["nombre"], 'correoUsuario' => $_POST["correo"], 'contraseniaUsuario' => $_POST["contrasenia"]));
        $insertaRst = inserta($valoresJson, "usuario");

        if (!$insertaRst['id']||!$insertaRst['success']) {
            $insertaRst['success'] = false;
            $insertaRst['msg'] = "Error preregistro: Informacion de preregistro no valida";
            echo json_encode($insertaRst);
            return;
        }

        $valoresSeleccionJson = json_encode(array('idUsuario' => $insertaRst["id"]));
        $seleccionaRst = selecciona($valoresSeleccionJson, "usuario");

        echo json_encode($seleccionaRst);
    }

    //OBJETIVO: Iniciar sesion
    //PARAMETROS: Se obtienen por post los parametros nombre, usuario y contrasenia
    //REGRESO: JSON con las empresas. El arreglo en "root" contiene idTienda, idUsuario, nombreUsuario, correoUsuario, puntos y estadoUsuario
    function iniciaSesion(){
        $valoresSeleccionJson = json_encode(array('correoUsuario' => $_SERVER['HTTP_AUTH_USER'], 'contraseniaUsuario' => $_SERVER["HTTP_AUTH_PW"]));
        $seleccionaRst = selecciona($valoresSeleccionJson, "correoUsuario");

        if ($seleccionaRst["records"]<=0) {
            $seleccionaRst['success']=false;
            $seleccionaRst['root']=null;
            $seleccionaRst['msg'] = "Error login: Usuario o contrasenia incorrecta";
            echo json_encode($seleccionaRst);
            return;
        }

        $idUsuario = $seleccionaRst["root"][0]["idUsuario"];

        $querySelecciona = "SELECT t.idTienda, u.idUsuario, u.nombreUsuario, u.correoUsuario, u.puntosUsuario AS puntos, u.estadoRegistro AS estadoUsuario FROM tienda t, usuario u WHERE t.idUsuario = u.idUsuario AND t.idUsuario = " . $idUsuario ;
        $seleccionaRst = executeQuery($querySelecciona);

        echo json_encode($seleccionaRst);
    }





?>