var app = angular.module('buscacep', ['ngResource'])
    .controller('myapp', function($scope, myResource) {
        $scope.cep = "", $scope.json_data = null;
        $scope.buscacep = function() {
            myResource.get({
                    'cep': $scope.cep
                }).$promise
                .then(function success(result) {
                    $scope.json_data = result;
                }).catch(function error(msg) {
                    console.error('Erro');
                });
        }
    }).factory('myResource', function($resource) {
        var rest = $resource(
            'https://viacep.com.br/ws/:cep/json/', {
                'cep': ''
            }
        );
        return rest;
    });