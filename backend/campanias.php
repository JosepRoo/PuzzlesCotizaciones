<?php
    REQUIRE_ONCE("./sqlConnections.php");
    REQUIRE_ONCE("./funcionesCRUD.php");
    session_start();
    
    switch($_POST['action']){
        case "getCampaniasPorEmpresa":
            getCampaniasPorEmpresa($_POST['idEmpresa']);
            break;
    }
    function getCampaniasPorEmpresa($idEmpresa) {
        $query = "SELECT c.idCampania AS id, c.nombreCampania AS nombre, c.estatusAprobacionCampania AS estatusAprovacion, c.creadoCampania AS creado, c.actualizadoCampania AS actualizado, g.idGrupo, g.nombreGrupo, e.nombreEmpleado FROM campania c, grupo g, empleado e WHERE c.idGrupo = g.idGrupo AND c.idEmpleado = e.idEmpleado AND e.idEmpresa = " . $idEmpresa;
        $queryRst = executeQuery($query);
        if (isset($_SESSION['error_sql']) && $_SESSION['error_sql'] != "") {
            $queryRst['success'] = false;
            $queryRst['messageText'] = $_SESSION['error_sql'];
        }
        confirm($queryRst['success']);
        echo json_encode($queryRst);
    }
?>