<!doctype html>
<html ng-app="scopeEx">
<head>
    <title>{{ title }}</title>

    <link href="//css.welapp.net/lib/angular/scopeEx" rel="stylesheet" type="text/css">
    <link href="//css.welapp.net/lib/angular/bower_components/bootstrap/dist/css/bootstrap.min" rel="stylesheet"
          type="text/css">
    <meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body>

<div class="show-scope-demo">
    <div ng-controller="GreetController">
        Hello {{name}}!
    </div>
    <div ng-controller="ListController">
        <ol>
            <li ng-repeat="name in names">{{name}} from {{department}}</li>
        </ol>
    </div>
</div>

<script src="//ajax.googleapis.com/ajax/libs/angularjs/1.4.5/angular.min.js"></script>
<script src="//js.welapp.net/lib/angular/scopeEx"></script>
</body>
</html>
