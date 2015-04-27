'use strict';
angular.module('apiApp', [
  	'ngRoute',
  	'apiApp.controllers',
  	'apiApp.services',
  ]).config([
  '$routeProvider', function($routeProvider) {
   	$routeProvider.when('/:apiFunction', {
   		templateUrl: 'tpl.html',
    	controller: 'ApiCtrl',
    }).otherwise({
    	redirectTo: '/getListDanhmuc'
    });
  }
]);
