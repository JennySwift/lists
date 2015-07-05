;(function(){
    'use strict';
    angular
        .module('lists')
        .directive('item', item);

    /* @inject */
    function item($parse, $http, $sce) {
        return {
            restrict: 'EA',
            scope: {
                "id": "@id",
                "object": "=object",
                "items": "=items"
            },
            templateUrl: 'js/directives/ItemTemplate.php',
            link: function($scope, elem, attrs) {
                $scope.currentIndex = 1;
                $scope.showDropdown = false;
                $scope.inputValue = '';

                $scope.getChildren = function ($item) {
                    var $url = $item.path;

                    $http.get($url).
                        success(function (response) {
                            var $children = response;
                            var $index = _.indexOf($scope.items, _.findWhere($scope.items, {id: $item.id}));
                            $scope.items[$index].children = $children;
                        }).
                        error(function (response) {
                            console.log("error");
                        });
                };

            }
        };
    }
}).call(this);

