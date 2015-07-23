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

        $scope.filter = function ($keycode) {
            if ($keycode !== 13) {
                return false;
            }
            var $typing = $("#filter").val();
            ListsFactory.filter($typing)
                .then(function (response) {
                    //console.log(response.data);
                    $scope.items = $scope.highlightLetters(response.data, $typing);
                })
                .catch(function (response) {

                });
        };

        $scope.highlightLetters = function ($response, $typing) {
            $typing = $typing.toLowerCase();

            for (var i = 0; i < $response.length; i++) {
                var $title = $response[i].title;
                var $index = $title.toLowerCase().indexOf($typing);
                var $substr = $title.substr($index, $typing.length);
                var $html = $title.replace($substr, '<span class="highlight">' + $substr + '</span>');
                $response[i].html = $html;
            }
            return $response;
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