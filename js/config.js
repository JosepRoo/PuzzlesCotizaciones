/**
 * INSPINIA - Responsive Admin Theme
 *
 * Inspinia theme use AngularUI Router to manage routing and views
 * Each view are defined as state.
 * Initial there are written state for all view in theme.
 *
 */
function config($stateProvider, $urlRouterProvider, $ocLazyLoadProvider) {
    $urlRouterProvider.otherwise("/index/principal");

    $ocLazyLoadProvider.config({
        // Set to true if you want to see what and when is dynamically loaded
        debug: false
    });

    $stateProvider

        .state('index', {
            abstract: true,
            url: "/index",
            templateUrl: "views/common/content.html",
        })
        .state('index.main', {
            url: "/main",
            templateUrl: "views/main.html",
            data: { pageTitle: 'Principal' }
        })
        .state('index.logeo', {
            url: "/logeo",
            templateUrl: "views/logeo.html",
            controller: 'logeoCtrl',
            data: { pageTitle: 'Logeo' }
        })
        .state('index.nuevoProyecto', {
            url: "/nuevoProyecto",
            templateUrl: "views/nuevoProyecto.html",
            controller: 'nuevoProyectoCtrl',
            data: { pageTitle: 'Nuevo Proyecto' }
        })
        .state('index.principal', {
            url: "/principal",
            templateUrl: "views/principal.html",
            controller: 'principalCtrl',
            data: { pageTitle: 'Principal' }
        });
}
angular
    .module('inspinia')
    .config(config)
    .run(function($rootScope, $state, $http) {
        $rootScope.$state = $state;

        // se ejecuta al cambiar de estado
        $rootScope.$on('$stateChangeStart', function (event, next, current) {

            // viaja por todas las peticiones http activas y las cancela
            $http.pendingRequests.forEach(function(request) {
                            
                if (request.cancel) {
                    request.cancel.resolve();
                }
            });
        });
    });
