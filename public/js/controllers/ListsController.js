var app = angular.module('lists');

(function () {
    app.controller('ListsController', function ($scope, $http, ListsFactory) {

        /**
         * scope properties
         */

        $scope.items = items;
        $scope.paths = {
            base: base_path,
            test: base_path + '/resources/views/test.php'
        };

        /**
         * watches
         */

        /**
         * select
         */

        $scope.getChildren = function ($item) {
            ListsFactory.getChildren($item)
                .then(function (response) {
                    var $children = response.data;
                    var $index = _.indexOf($scope.items, _.findWhere($scope.items, {id: $item.id}));
                    $scope.items[$index].children = $children;
                    console.log(response.data);
                })
                .catch(function (response) {

                });
        };

        /**
         * insert
         */

        /**
         * update
         */

        /**
         * delete
         */

        /**
         * other
         */

        $scope.closePopup = function ($event, $popup) {
            var $target = $event.target;
            if ($target.className === 'popup-outer') {
                $scope.show.popups[$popup] = false;
            }
        };

    }); //end controller

})();