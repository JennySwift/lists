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
        $scope.new_item = {
            title: '',
            body: ''
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

        $scope.goHome = function () {
            ListsFactory.goHome()
                .then(function (response) {
                    $scope.showHome(response);
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
                    $scope.showChildren(response, $item);
                })
                .catch(function (response) {

                });
        };

        $scope.showChildren = function (response, $item) {
            $item.children = response.data.children;
            $scope.items = [$item];
            $scope.breadcrumb = response.data.breadcrumb;
            $scope.zoomed_item = $item;
        };

        $scope.insertItem = function () {
            ListsFactory.insertItem($scope.zoomed_item, $scope.new_item)
                .then(function (response) {
                    if ($scope.zoomed_item) {
                        $scope.showChildren(response, $scope.zoomed_item);
                    }
                    else {
                        $scope.showHome(response);
                    }
                })
                .catch(function (response) {

                });
        };

        $scope.showHome = function (response) {
            $scope.items = response.data;
            $scope.zoomed_item = null;
            $scope.breadcrumb = [];
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
         * update
         */

        /**
         * delete
         */

        $scope.deleteItem = function ($item) {
            ListsFactory.deleteItem($item)
                .then(function (response) {
                    if (!$item.parent_id) {
                        $scope.items = _.without($scope.items, $item);
                        return true;
                    }
                    else {
                        /**
                         * @VP:
                         * Why is $parent undefined? If I set a breakpoint on 'return $parent' in $scope.findParent,
                         * $parent is the correct value, but then the debugger lands there again and it is undefined.
                         */
                        var $parent = $scope.findParent($scope.items, $item);
                        //console.log($parent);
                    }
                })
                .catch(function (response) {

                });
        };

        $scope.findParent = function($array, $item) {
            var $parent;
             $($array).each(function () {
                if (this.id === $item.parent_id) {
                    this.children = _.without(this.children, $item);
                    return $parent = this;
                }
                if (this.children) {
                    $scope.findParent(this.children, $item);
                }
            });
            return $parent;
        };

        //$scope.deleteJsItem = function () {
        //
        //};

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