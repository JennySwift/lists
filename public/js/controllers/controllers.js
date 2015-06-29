var app = angular.module('lists', ['ngSanitize', 'ngAnimate', 'checklist-model'], function ($interpolateProvider) {
    $interpolateProvider.startSymbol('[[');
    $interpolateProvider.endSymbol(']]');
});

(function () {
    app.controller('controller', function ($scope) {

    }); //end controller

})();