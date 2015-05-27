'use strict';

/* Controllers */

var $config = {
  getInfo:{
    requestAction: 'address/getInfo',
    params:[
      {slug:'id',type:'text',required:true,options:[]}
    ],
  },
  
  getInfo:{
    requestAction: 'address/getInfo',
    params:[
      {slug:'id',type:'text',required:true,options:[]}
    ],
  },

  getInfo:{
    requestAction: 'address/getInfo',
    params:[
      {slug:'id',type:'text',required:true,options:[]}
    ],
  },

  getInfo:{
    requestAction: 'address/getInfo',
    params:[
      {slug:'id',type:'text',required:true,options:[]}
    ],
  },
};

angular.module('apiApp.controllers',[]).
    controller('ApiCtrl', function($scope, $routeParams, $location, $window, $http, apiService, apiUrl) {
      var $apiFunction  = $scope.apiFunction  = $routeParams.apiFunction;

      if (typeof $config[$apiFunction] != "undefined") {    
        $scope.apiModule  = $config[$apiFunction].apiModule;
            
        $scope.params = [];
        for (var i=0; i < $config[$apiFunction].params.length; i++) {
          $scope.params.push( 
                              { 
                                name : $config[$apiFunction].params[i].slug, 
                                value : ($config[$apiFunction].params[i].type == 'select') ? $config[$apiFunction].params[i].options[0] : null,
                                type: $config[$apiFunction].params[i].type,
                                options: $config[$apiFunction].params[i].options,
                                required: $config[$apiFunction].params[i].required,
                                placeholder: $config[$apiFunction].params[i].placeholder ? $config[$apiFunction].params[i].placeholder : 'Param value'
                              } 
                            );
        };
        $scope.requestAction  = apiUrl + $config[$apiFunction].requestAction;

      } else{
        $scope.params = [ { name : '', value : '', type : '', options:[]} ];
      };
      
      $scope.goToApi  = function(apiSlug){
        console.log(apiSlug);
        $location.path('/#/'+apiSlug);
      };

      $scope.checkActiveTab = function(menu){
        var checkSubmenu  =   function(subMenus){
          if (subMenus && subMenus.length > 0) {
            for (var i = 0; i < subMenus.length; i++) {
              if (subMenus[i].slug == $scope.apiFunction || checkSubmenu(subMenus[i].subMenus)) {
                return true;
                break;
              }
            };
          };
          return false;
        };
        if ($scope.apiFunction == menu.slug) {
          return true;
        } else{
          return checkSubmenu(menu.subMenus);
        };
      };  
      
      $scope.resetParams  = function(){
        for (var i = 0; i < $scope.params.length; i++) {
          $scope.params[i].value  = '';
        };
      };

      $scope.addParam = function($event){        
          $scope.params.push( { name : '', value : '',} );
      };
      
      $scope.removeParam = function(idx) {
      $scope.params.splice(idx, 1);
    };

    $scope.excute = function(){
      var $paramsRequested  = {};
      for (var i=0;i<$scope.params.length;i++)
      {
        $paramsRequested[$scope.params[i].name] = ($scope.params[i].type == 'select') ? $scope.params[i].value.value : $scope.params[i].value;
      }

      $scope.paramsRequested  = $paramsRequested;

      apiService.excute($paramsRequested, $scope.requestAction, function(results){
        console.log(results);
        $scope.results  = results;
      }, function(data, status){
        console.log(data);
        $scope.results  = data;
        $scope.http_status_code   = status;
      });

      
    };
});