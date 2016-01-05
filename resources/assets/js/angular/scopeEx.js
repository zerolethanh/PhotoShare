/**
 * Created by ZE on 28/09/15.
 */
angular.module('scopeEx', [])
    .controller('GreetController', ['$rootScope', '$scope', function ($rootScope, $scope) {
        $rootScope.title = 'Angular';
        $scope.name = 'World';
        $rootScope.department = 'Angular';
    }])
    .controller('ListController', ['$scope', function ($scope) {
        $scope.names = ['Long', 'Thanh', 'Nga'];
    }]);
