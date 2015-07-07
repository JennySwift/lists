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
                    $item.children = response.data.children;
                })
                .catch(function (response) {

                });
        };

        $scope.collapseItem = function ($item) {
            $item.children = [];
        };

        $scope.zoom = function ($item) {
            ListsFactory.getChildren($item)
                .then(function (response) {
                    $item.children = response.data.children;
                    $scope.items = [$item];
                    $scope.breadcrumb = response.data.breadcrumb;
                })
                .catch(function (response) {

                });
        };

        $scope.filter = function () {
            ListsFactory.filter()
                .then(function (response) {
                    //console.log(response.data);
                    $scope.items = response.data;
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