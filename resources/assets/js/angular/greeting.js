var myApp = angular.module('myApp', []);

myApp.controller('GreetingController', ['$scope', function ($scope) {
    $scope.greeting = 'xin chao!';
}]);
myApp.controller('DoubleController', ['$scope', function ($scope) {
    $scope.num = 0;
    $scope.double = function (value) {
        return value * 2;
    };
}]);
myApp.controller('MyController', ['$scope', function ($scope) {
    $scope.username = 'thanh';
    $scope.sayHello = function () {
        $scope.greeting = 'Hello ' + $scope.username + "!";
    }
}]);