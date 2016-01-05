<!doctype html>
<html>
<head>
    <title>{{ greeting }}</title>
    <script src="//ajax.googleapis.com/ajax/libs/angularjs/1.4.5/angular.min.js"></script>

    <script src="//js.welapp.net/lib/angular/greeting.js"></script>
</head>
<body ng-app="myApp">

<div ng-controller="GreetingController">
    {{ greeting }}
</div>
<div ng-controller="DoubleController">
    Two times <input ng-model="num"> equals {{ double(num) }}
</div>

<div ng-controller="MyController">
    Your name:
    <input type="text" ng-model="username">
    <button ng-click='sayHello()'>greet</button>
    <hr>
    {{greeting}}
</div>

</body>
</html>