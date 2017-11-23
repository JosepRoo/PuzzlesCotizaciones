angular
	.module('inspinia')
	.controller('MainCtrl', MainCtrl)
	.controller('principalCtrl', principalCtrl)
	.controller('nuevoProyectoCtrl', nuevoProyectoCtrl)
	.controller('logeoCtrl', logeoCtrl);


//Controlador principal, html recibe los atributos con el objeto 'main'
function MainCtrl($state, $scope) {

	//Revisa si el usuario ya esta logeado de lo contrario redirecciona a /logeo y oculta los html comunes
	if (localStorage.getItem('usuario') === null) {

		$scope.logeo = false;
		$state.go("index.logeo");
		$('#page-wrapper').css("margin-left", "0px");

	} else {

		var usuario = $.parseJSON(localStorage.getItem('usuario'));
		$('#page-wrapper').css("margin-left", "");
		$scope.logeo = true;
		$scope.nombreUsuario = usuario.Nombre;
		console.log("$scope.nombreUsuario", $scope.nombreUsuario);
		$scope.apellidoUsuario = usuario.Apellido;
		$scope.empresa = usuario.empresa;

	}

	$scope.$on('revisar-logeo', function (data) {

		if (localStorage.getItem('usuario') === null) {

			$scope.logeo = false;
			$('#page-wrapper').css("margin-left", "0px");
			$state.go("index.logeo");

		} else {

			var usuario = $.parseJSON(localStorage.getItem('usuario'));
			$('#page-wrapper').css("margin-left", "");
			$scope.logeo = true;
			$scope.nombreUsuario = usuario.Nombre;
			console.log("$scope.nombreUsuario", $scope.nombreUsuario);
			$scope.apellidoUsuario = usuario.Apellido;
			$scope.empresa = usuario.empresa;

		}

	});

}

//Controlador para la pantalla principal
function principalCtrl($scope, $location, $state, $rootScope, $http, $q) {

	console.log("Controlador:", this);


	//Revisa si el usuario ya esta logeado de lo contrario redirecciona a /logeo -> broadcast.on en MainCtrl
	$rootScope.$broadcast('revisar-logeo');

		var cancel = $q.defer();
		$http({
			url: 'backend/trearProyectos.php',
			method: 'POST',
			headers: {
				"Content-Type": "application/x-www-form-urlencoded",
				"X-Login-Ajax-call": 'true'
			},
			timeout: cancel.promise, // cancel promise, standard thing in $http request
			cancel: cancel

		}).success(function (res) {

			if (res.success == true) {

				$scope.proyectos = res.root;
				for (var i = 0; i < $scope.proyectos.length; i++){
					$scope.proyectos[i].datos = JSON.parse($scope.proyectos[i].datos);
				}
				console.log("$scope.proyectos", $scope.proyectos);

			} else {

			}

		});

}

//Controlador para la pantalla de LogIn
function logeoCtrl($scope, $state, $rootScope, $http, $q) {

	console.log("Controlador:", this);

	//Revisa si el usuario ya esta logeado de lo contrario redirecciona a /logeo -> broadcast.on en MainCtrl
	$rootScope.$broadcast('revisar-logeo');

	if (localStorage.getItem('usuario') !== null) {
		$state.go("index.principal");
	}

	$scope.usuario = {};
	$scope.error = false;

	//Ejecuta la peticion para el logeo con los datos de $scope.usaurio
	$scope.logeo = function () {

		$scope.error = false;

		var cancel = $q.defer();
		$http({
			url: 'backend/usuarios.php',
			method: 'POST',
			headers: {
				"Content-Type": "application/x-www-form-urlencoded",
				"X-Login-Ajax-call": 'true'
			},
			timeout: cancel.promise, // cancel promise, standard thing in $http request
			cancel: cancel,
			data: 'action=logeo&usuario=' + JSON.stringify($scope.usuario)

		}).success(function (res) {

			if (res.success == true) {

				localStorage.setItem("usuario", JSON.stringify(res.root[0]));
				$rootScope.$broadcast('revisar-logeo');
				$state.go("index.principal");
				console.log(res.root[0]);

			} else {

				$scope.error = true;
				$scope.errorTexto = res.messageText;

			}

		});

	}; //Falta codigo para "Olvide la contraseña"

}

//Controlador para la pantalla de nuevoProyecto
function nuevoProyectoCtrl($scope, $state, $rootScope, $http, $q) {

	console.log("Controlador:", this);

	//Revisa si el usuario ya esta logeado de lo contrario redirecciona a /logeo -> broadcast.on en MainCtrl
	$rootScope.$broadcast('revisar-logeo');

	$scope.proyecto = {};
	$scope.validate = true;
	$scope.proyecto.presupuesto = 0;
	$scope.proyecto.duracion = 0;
	$scope.languages = [
		{
			name: "Access",
			value: 38
		},
		{
			name: "APL",
			value: 32
		},
		{
			name: "BASIC",
			value: 150
		},
		{
			name: "C",
			value: 128
		},
		{
			name: "C++",
			value: 55
		},
		{
			name: "COBOL",
			value: 91
		},
		{
			name: "Fortran",
			value: 77
		},
		{
			name: "High Level Language",
			value: 64
		},
		{
			name: "HTML",
			value: 15
		},
		{
			name: "Java",
			value: 53
		}
	];

	$scope.proyecto.gscAsks = [
		{
			pregunta: "Cuantas facilidades existen para ayudar la comunicacion, transferencia o intercambio de informacion con el sistema?",
			valor: 1
		},
		{
			pregunta: "Como se maneja la distribucion y procesamiento de informacion?",
			valor: 1
		},
		{
			pregunta: "Se requiere tener un buen tiempo de respuesta al usuario?",
			valor: 1
		},
		{
			pregunta: "Que tanto es usado el hardware donde el sistema sera ejecutado?",
			valor: 1
		},
		{
			pregunta: "Que tan frecuente las transacciones son ejecutadas? (diario, semanal, mensual , etc)",
			valor: 1
		},
		{
			pregunta: "Que porcentaje de la informacion es inyectada en linea?",
			valor: 1
		},
		{
			pregunta: "El sistema esta desarrollado para ser eficiente a nivel usuario final?",
			valor: 1
		},
		{
			pregunta: "Cuanta informacion es actualizada en linea?",
			valor: 1
		},
		{
			pregunta: "El sistema ejecutara procesos logicos o matematicos complejos?",
			valor: 1
		},
		{
			pregunta: "El sistema tendra que ser capaz de atender necesidades diferentes por usuario?",
			valor: 1
		},
		{
			pregunta: "Cual es la dificultad de instalacion?",
			valor: 1
		},
		{
			pregunta: "Que tan efectivos deber ser los procesos de recuperacion y respaldo de datos?",
			valor: 1
		},
		{
			pregunta: "El sistema sera instalado o utilizado en diferentes sitios o por diferentes organizaciones?",
			valor: 1
		},
		{
			pregunta: "El sistema debe ser flexible y adaptable a cambios?",
			valor: 1
		}
	];

	$scope.proyecto.usuario = JSON.parse(localStorage.getItem('usuario'));

	$scope.sistemaLogeo = function(res) {

		$scope.proyecto.LogIn = res;

		if (res == 0) {
			$("#logeo").css("box-shadow", '5px 5px 5px 5px rgb(204, 104, 127)');
			$scope.proyecto.camposLogIn = 0;
			$scope.proyecto.showCamposLogIn = 0;

			
		} else {
			$("#logeo").css("box-shadow", '5px 5px 5px 5px #49b7b2c9');
			$scope.proyecto.camposLogIn = 1;
			$scope.proyecto.showCamposLogIn = 1;

		}

	};

	$scope.formualarios = function(res) {

		$scope.proyecto.formularios = res;

		if (res == 0) {
			$("#formularios").css("box-shadow", '5px 5px 5px 5px rgb(204, 104, 127)');
			$scope.proyecto.camposFormularios = 0;
			$scope.proyecto.showCamposFormularios = 0;

			
		} else {
			$("#formularios").css("box-shadow", '5px 5px 5px 5px #49b7b2c9');
			$scope.proyecto.camposFormularios = 1;
			$scope.proyecto.showCamposFormularios = 1;

		}

	};

	$scope.visualizacion = function(res) {

		$scope.proyecto.visualizacion = res;

		if (res == 0) {
			$("#visualizacion").css("box-shadow", '5px 5px 5px 5px rgb(204, 104, 127)');
			$scope.proyecto.camposVisualizacion = 0;
			$scope.proyecto.showCamposVisualizacion = 0;

			
		} else {
			$("#visualizacion").css("box-shadow", '5px 5px 5px 5px #49b7b2c9');
			$scope.proyecto.camposVisualizacion = 1;
			$scope.proyecto.showCamposVisualizacion = 1;

		}

	};

	$scope.exportacion = function(res) {

		$scope.proyecto.exportacion = res;

		if (res == 0) {
			$("#exportacion").css("box-shadow", '5px 5px 5px 5px rgb(204, 104, 127)');
			$scope.proyecto.camposExportacion = 0;
			$scope.proyecto.showCamposExportacion = 0;

			
		} else {
			$("#exportacion").css("box-shadow", '5px 5px 5px 5px #49b7b2c9');
			$scope.proyecto.camposExportacion = 1;
			$scope.proyecto.showCamposExportacion = 1;

		}

	};

	$scope.cohesion = function(res) {

		$scope.proyecto.cohesion = res;

		if (res == 0) {
			$("#cohesion").css("box-shadow", '5px 5px 5px 5px rgb(204, 104, 127)');
			$scope.proyecto.camposCohesion = 0;
			$scope.proyecto.showCamposCohesion = 0;

			
		} else {
			$("#cohesion").css("box-shadow", '5px 5px 5px 5px #49b7b2c9');
			$scope.proyecto.camposCohesion = 1;
			$scope.proyecto.showCamposCohesion = 1;

		}

	};


	$scope.api = function(res) {

		$scope.proyecto.api = res;

		if (res == 0) {
			$("#api").css("box-shadow", '5px 5px 5px 5px rgb(204, 104, 127)');
			$scope.proyecto.camposApi = 0;
			$scope.proyecto.showCamposApi = 0;

			
		} else {
			$("#api").css("box-shadow", '5px 5px 5px 5px #49b7b2c9');
			$scope.proyecto.camposApi = 1;
			$scope.proyecto.showCamposApi = 1;

		}

	};

	$scope.ei = function(entidad, parametros){

		entidad = Number(entidad);
		parametros = Number(parametros);

		if (entidad != null && parametros != null)
		switch (entidad) {
			case 1:
				switch (parametros) {
					case 3:
						return 3;
	
					case 10:
						return 3;
	
					case 16:
						return 4;
	
				};
			case 2:
				switch (parametros) {
					case 3:
						return 3;
	
					case 10:
						return 4;
	
					case 16:
						return 6;
	
				};
			case 3:
				switch (parametros) {
					case 3:
						return 4;
	
					case 10:
						return 6;
	
					case 16:
						return 6;
	
				}
		}
		return 0;
	};

	$scope.ilf = function(entidad){
		entidad = Number(entidad);

		if (entidad != null )
		switch(entidad){
			case 1: 
				return 7;
			case 2: 
				return 7;
			case 3: 
				return 7;
			case 4: 
				return 10;
			case 5: 
				return 10;
			case 6: 
				return 10;
			case 7: 
				return 15;
			case 8: 
				return 15;
			case 9: 
				return 15;
		}
		return 0;
	};

		$scope.elf = function(entidad){
		entidad = Number(entidad);
			
		if (entidad != null )

		switch(entidad){
			case 1: 
				return 5;
			case 2: 
				return 5;
			case 3: 
				return 7;
			case 4: 
				return 7;
			case 5: 
				return 10;
		}
		return 0;
	};

	$scope.calcularPuntosFuncion = function() {
		console.log($scope.proyecto);
		$scope.proyecto.puntosFuncion = ($scope.ei(1,$scope.proyecto.camposLogIn) + $scope.ei($scope.proyecto.formularios,$scope.proyecto.camposFormularios) + $scope.ei($scope.proyecto.visualizacion,$scope.proyecto.camposVisualizacion) + $scope.ei($scope.proyecto.exportacion,$scope.proyecto.camposExportacion) + $scope.ilf($scope.proyecto.camposCohesion) + $scope.elf($scope.proyecto.camposApi))*3;  
		console.log("$scope.proyecto.puntosFuncion", $scope.proyecto.puntosFuncion);
		$scope.validate = false;
	};

	$scope.calcularVAF = function() {
		var sum = 0;
		for (var i=0; i<$scope.proyecto.gscAsks.length; i++){
			sum += Number($scope.proyecto.gscAsks[i].valor);
		};
		$scope.proyecto.gsc = sum;
		$scope.proyecto.vaf = 0.65 + ($scope.proyecto.gsc/100);
		$scope.proyecto.fp = $scope.proyecto.vaf * $scope.proyecto.puntosFuncion;
	}

	$scope.presupuesto = function() {
		if ($scope.proyecto.lenguaje && $scope.proyecto.personas){
			console.log($scope.proyecto);
			$scope.proyecto.duracion = ((($scope.proyecto.fp*$scope.proyecto.lenguaje.value)/80)/$scope.proyecto.personas);
			$scope.proyecto.presupuesto = $scope.proyecto.duracion* 20000 * ($scope.proyecto.personas/2);
		}
	}

	$scope.guardar = function() {

		var cancel = $q.defer();
		$http({
			url: 'backend/guardarProyecto.php',
			method: 'POST',
			headers: {
				"Content-Type": "application/x-www-form-urlencoded",
				"X-Login-Ajax-call": 'true'
			},
			timeout: cancel.promise, // cancel promise, standard thing in $http request
			cancel: cancel,
			data: 'proyecto='+JSON.stringify($scope.proyecto)

		}).success(function (res) {

			if (res.success == true) {
				$state.go("index.principal");


			} else {


			}

		});

	}

}

