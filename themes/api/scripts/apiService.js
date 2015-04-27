'use strict';

/* Services 
'http://localhost/TagBook/tagbook_new/api/'
*/
angular.module('apiApp.services', [])
	// .value('apiUrl', 'http://128.199.69.38/elistenaz/')
	.value('apiUrl', 'http://localhost/elistening/')
	.factory('apiService', function($http, apiUrl){
		return	{
			excute:	function($data, $action, callback_success, callback_error){	
				var $dataToSend;
				var fd = new FormData();
		     	angular.forEach($data, function(value, key) {
		     		fd.append(key, value);
		     	});
				$dataToSend 	=	fd;
		        $http.post($action, $dataToSend, {
		            transformRequest: angular.identity,
		            //headers: {'Content-Type': undefined, 'auth_token' : $auth_token},
		            headers: {'Content-Type': undefined}
		        })
		        .success(callback_success)
		        .error(callback_error);	
			},
			upload:	function($data, $action, callback_success, callback_error){	
		        var formData = new FormData();
				angular.forEach($data, function(value, key) {
                  formData.append(key, value);
                });
		        return $http.post($action, formData, {
		            transformRequest: angular.identity,
		            headers: {'Content-Type': undefined}
		        })
		        .success(callback_success)
		        .error(callback_error);	
			},
		}
	})