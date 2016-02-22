(function() {
    "use strict";

    angular.module('codeChatApp', ['ngResource'])
        .controller('CodeCompilerCtrl', function($scope, $http, $resource) {
            var api_path = config.apiPath;
            var lang = "c";
            $scope.code = templates[lang];

            $scope.submitCode =  function() {
                console.log("Submitting code...");
                var params = {
                    'code': $scope.code,
                    'input': $scope.input,
                    'lang': lang
                };
                console.log(params);
                callAPI(params);
            };

            //hit api for code compilation and running
            var callAPI = function(params) {
                console.log(api_path);
                var resource = $resource(api_path);

                resource.get(params, function(response) {
                    // console.log(response);
                    $scope.output = response.msg;
                    console.log(response.msg);
                }, function(response) {
                    console.log("error");
                }).$promise.finally(function(){
                    
                });
            };
        });
}());
