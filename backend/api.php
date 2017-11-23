	<?php
		header("Content-Type: application/json");
		
		$pathArr = explode("/", $_SERVER['PATH_INFO']);
		array_shift($pathArr);

		$archivo = $pathArr[0];

		switch ($archivo) {
			case 'seccion':
				llamarSecciones($pathArr);
				break;
			case 'preguntas':
				llamarPreguntas($pathArr);
				break;
			case 'empresas':
				llamarEmpresas($pathArr);
				break;
			case 'recompensas':
				llamarRecompensas($pathArr);
				break;
			case 'grupos':
				llamarGrupos($pathArr);
				break;
			case 'campania':
				llamarCampania($pathArr);
				break;
			default:
				llamarRegistro($pathArr);
				break;
		}
		
		function llamarEmpresas($parametroArr){
			REQUIRE_ONCE("./empresas.php");

			array_shift($parametroArr);

			$llamada = $parametroArr[0];

			switch ($llamada) {
				default:
					getEmpresas($parametroArr[0]); //localhost/api.php/empresas/{idTienda}
					break;
			}

		}

		function llamarSecciones($parametroArr){
			REQUIRE_ONCE("./secciones.php");

			array_shift($parametroArr);

			$llamada = $parametroArr[0];

			switch ($llamada) {
				case 'notificaciones':
					if ($parametroArr[2]==null) {
						getNotificaciones($parametroArr[1]); //localhost/api.php/seccion/notificaciones/{idTienda}
					}else{
						getNotificacionesEmpresa($parametroArr[1], $parametroArr[2]); //localhost/api.php/seccion/notificaciones/{idTienda}/{idEmpresa}
					}

					break;

				case 'contenido':
					getInformacionSeccion($parametroArr[1], $parametroArr[2], $parametroArr[3]);  //localhost/api.php/seccion/contenido/{idTienda}/{idEmpresa}/{tipoContenido}
					break;

				case 'setVisto':
					setContenidoValidacion($parametroArr[1], $parametroArr[2], "visto"); //localhost/api.php/seccion/setVisto/{idTienda}/{idContenido}
					break;

				case 'setContestado':
					setContenidoValidacion($parametroArr[1], $parametroArr[2], "contestado"); //localhost/api.php/seccion/setContestado/{idTienda}/{idContenido}
					break;

				case 'setNoVisto':
					setContenidoValidacion($parametroArr[1], $parametroArr[2], "noVisto"); //localhost/api.php/seccion/setNoVisto/{idTienda}/{idContenido}
					break;
				
				default:
					# code...
					break;
			}

		}

		function llamarRecompensas($parametroArr){
			REQUIRE_ONCE("./recompensas.php");

			array_shift($parametroArr);

			$llamada = $parametroArr[0];

			switch ($llamada) {
				default:
					getRecompensas($parametroArr[0]);  //localhost/api.php/recompensas/{idTienda}
					break;
			}

		}

		function llamarPreguntas($parametroArr){
			REQUIRE_ONCE("./preguntas.php");

			array_shift($parametroArr);

			$llamada = $parametroArr[0];

			switch ($llamada) {
				default:
					getPreguntas($parametroArr[0]);  //localhost/api.php/preguntas/{idContenido}
					break;
			}

		}

		function llamarGrupos($parametroArr){
			REQUIRE_ONCE("./grupos.php");

			array_shift($parametroArr);

			$llamada = $parametroArr[0];

			if ($_SERVER['REQUEST_METHOD'] === 'POST') {
				switch ($llamada) {
					default:
						$arrPostParams = json_decode(file_get_contents("php://input"), true);
						crearGrupo($arrPostParams);  //localhost/api.php/grupos/
						break;
				}
			}
		}

		function llamarCampania($parametroArr){
			REQUIRE_ONCE("./campanias.php");

			array_shift($parametroArr);

			$llamada = $parametroArr[0];

			if ($_SERVER['REQUEST_METHOD'] === 'POST') {
				switch ($llamada) {
					default:
						$arrPostParams = json_decode(file_get_contents("php://input"), true);
						crearCampania($arrPostParams);  //localhost/api.php/campania/
						break;
				}
			}
		}

		function llamarRegistro($parametroArr){
			REQUIRE_ONCE("./registro.php");

			$llamada = $parametroArr[0];

			switch ($llamada) {
				case 'registro':
					$_POST = json_decode(file_get_contents("php://input"), true);
					preregistro();  //localhost/api.php/registro/
					break;
				case 'login':
					iniciaSesion();  //localhost/api.php/login/
					break;
				default:
					
			}

		}

	?>