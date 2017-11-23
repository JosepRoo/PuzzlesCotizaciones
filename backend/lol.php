<?php
	REQUIRE_ONCE("./sqlConnections.php");
	ini_set("display_errors", 1);
	$rst1 = executeQuery("SELECT idEstado, nombreEstado FROM  estado");
	for($i=0;$i<$rst1['records'];$i++){
		$rst2 = executeQuery("SELECT idMunicipio,municipio,nombreMunicipio FROM municipio WHERE idEstado = ".$rst1['root'][$i]['idEstado']);
		for($j=0;$j<$rst2['records'];$j++){
			$rst3 = executeQuery("SELECT idAsentamiento, codigoPostalAsentamiento, asentamiento,nombreAsentamiento FROM asentamiento WHERE idMunicipio = ".$rst2['root'][$j]['idMunicipio']);
			$rst2['root'][$j]['asentamientos'] = $rst3['root'];
		}
		$rst1['root'][$i]['municipios'] = $rst2['root'];

	}
	echo json_encode($rst1);

?>