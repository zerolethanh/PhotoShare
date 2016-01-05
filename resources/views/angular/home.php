<!doctype html>
<html>
<head>
    <title>My Angular App</title>
    <script src="//ajax.googleapis.com/ajax/libs/angularjs/1.4.5/angular.min.js"></script>

    <script src="//js.welapp.net/lib/angular/invoice3.js"></script>
    <script src="//js.welapp.net/lib/angular/finance3.js"></script>
</head>
<body>

<div ng-app="invoice3" ng-controller="InvoiceController as invoice">
    <b>Invoice:</b>

    <div>
        Quantity: <input type="number" min="0" ng-model="invoice.qty" required>
    </div>
    <div>
        Costs: <input type="number" min="0" ng-model="invoice.cost" required>
        <select ng-model="invoice.inCurr">
            <option ng-repeat="c in invoice.currencies">{{c}}</option>
        </select>
    </div>
    <div>
        <b>Total:</b>
    <span ng-repeat="c in invoice.currencies">
      {{invoice.total(c) | currency:c}}
    </span>
        <button class="btn" ng-click="invoice.pay()">Pay</button>
    </div>
</div>

</body>
</html>