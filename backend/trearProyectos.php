<?php
// Your token
require ("sqlConnections.php");
session_start();
date_default_timezone_set("America/Mexico_City");

$proyecto = json_decode($_POST['proyecto']);

// Print the date from the response

$query = "SELECT * FROM Proyecto";

$queryRst = executeQuery($query);


if (isset($_SESSION['error_sql']) && $_SESSION['error_sql'] != "") {

    $queryRst['success'] = false;
    $queryRst['messageText'] = $_SESSION['error_sql'];
}

confirm($queryRst['success']);
echo json_encode($queryRst);


?>