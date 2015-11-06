<!doctype html>
<html data-ng-app="">
<head>
<meta charset="utf-8">
<title>Untitled Document</title>
<script src="js/angular.min.js"></script>
</head>

<body data-ng-init="customers=['Daves','Napur','Mahesh','Dipesh']">
	name:
    <br>
    <input type="text" data-ng-model="name"/>{{name}}
    
    <div class="container" >
    	<h3>Looping with the ng repeat directive</h3>
        	<ul>
            	<li data-ng-repeat="name in customers | filter:name">{{name}}</li>
            </ul>
    </div>
</body>
</html>
