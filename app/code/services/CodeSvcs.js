(function () {
    "use strict";
    angular.module('codeChatApp')
        .factory('CodeSvcs', function ($resource) {
            var apiPath = config.apiPath

            return $resource(apiPath, {
            });
        });
}());
