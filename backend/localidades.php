<?php

    REQUIRE_ONCE("./sqlConnections.php");
    REQUIRE_ONCE("./funcionesCRUD.php");

    session_start();

    switch($_POST['action']){

        case "getMunicipios":
            getMunicipios();
            break;
        
    }

    function getEstados() {

        $query = "SELECT * from estado";
        $queryRst = executeQuery($query);
        if (isset($_SESSION['error_sql']) && $_SESSION['error_sql'] != "") {
          $queryRst['success'] = false;
          $queryRst['messageText'] = $_SESSION['error_sql'];
        }
        confirm($queryRst['success']);
        echo json_encode($queryRst);

    }


?>