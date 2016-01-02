var app = angular.module('lists');

(function () {
    app.controller('ItemsController', function ($rootScope, $scope, $http, ItemsFactory, FeedbackFactory, SortableFactory) {

        /**
         * scope properties
         */

        $scope.items = items;
        $scope.categories = categories;
        $scope.favourites = favourites;
        $scope.paths = {
            base: base_path,
            test: base_path + '/resources/views/test.php'
        };
        $scope.new_item = {
            title: '',
            body: ''
        };
        $scope.show = {
            popups: {
                item: false
            },
            favourites: false
        };
        $scope.newIndex = -1;
        $scope.feedbackFactory = FeedbackFactory;
        $scope.feedback_messages = [];

        /**
         * watches
         */

        $scope.$watch('feedbackFactory.data', function (newValue, oldValue, scope) {
            if (newValue && newValue.message) {
                scope.provideFeedback(newValue.message);
            }
        });

        $scope.provideFeedback = function ($message, $type) {
            var $new = {
                message: $message,
                type: $type
            };

            $scope.feedback_messages.push($new);

            //$scope.feedback_messages.push($message);

            setTimeout(function () {
                $scope.feedback_messages = _.without($scope.feedback_messages, $new);
                $scope.$apply();
            }, 3000);
        };

        /**
         * select
         */

        function getPinnedItems () {
            $rootScope.showLoading();
            ItemsFactory.getPinnedItems()
                .then(function (response) {
                    $scope.pinnedItems = response.data;
                    $rootScope.hideLoading();
                })
                .catch(function (response) {
                    $rootScope.responseError(response);
                });
        }

        getPinnedItems();

        $scope.toggleFavourites = function () {
            $scope.show.favourites = !$scope.show.favourites;
        };

        $scope.getChildren = function ($item) {
            $rootScope.showLoading();
            ItemsFactory.getChildren($item)
                .then(function (response) {
                    $item.children = response.data.children;
                    $rootScope.hideLoading();
                })
                .catch(function (response) {
                    $rootScope.responseError(response);
                });
        };

        $scope.goHome = function () {
            $rootScope.showLoading();
            ItemsFactory.goHome()
                .then(function (response) {
                    $scope.showHome(response);
                    $rootScope.hideLoading();
                })
                .catch(function (response) {
                    $rootScope.responseError(response);
                });
        };

        $scope.collapseItem = function ($item) {
            $item.children = [];
        };

        $scope.zoom = function ($item) {
            $rootScope.showLoading();
            ItemsFactory.getChildren($item)
                .then(function (response) {
                    $scope.showChildren(response, $item);
                    $rootScope.hideLoading();
                })
                .catch(function (response) {
                    $rootScope.responseError(response);
                });
        };

        $scope.goToFavourite = function ($favourite) {
            $scope.zoom($favourite);
            $scope.show.favourites = false;
        };

        $scope.showChildren = function (response, $item) {
            $item.children = response.data.children;
            $scope.items = [$item];
            $scope.breadcrumb = response.data.breadcrumb;
            $scope.zoomed_item = $item;
        };

        $scope.insertItem = function ($keycode) {
            if ($keycode !== 13) {
                return false;
            }

            $rootScope.showLoading();
            ItemsFactory.insertItem($scope.zoomed_item, $scope.new_item)
                .then(function (response) {
                    if ($scope.zoomed_item) {
                        $scope.showChildren(response, $scope.zoomed_item);
                    }
                    else {
                        $scope.showHome(response);
                    }
                    $scope.clearNewItemFields();
                    $scope.provideFeedback('Item added');
                    $rootScope.hideLoading();
                })
                .catch(function (response) {
                    $rootScope.responseError(response);
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
            $rootScope.showLoading();
            ItemsFactory.filter($typing)
                .then(function (response) {
                    $scope.items = $scope.highlightLetters(response.data, $typing);
                    $rootScope.hideLoading();
                })
                .catch(function (response) {
                    $rootScope.responseError(response);
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
            $rootScope.showLoading();
            ItemsFactory.deleteItem($item)
                .then(function (response) {
                    var $parent = $scope.findParent($scope.items, $item);
                    $scope.provideFeedback('Item deleted');
                    $scope.deleteJsItem($parent, $item);
                    $rootScope.hideLoading();
                    $scope.closeItemPopup();
                })
                .catch(function (response) {
                    $rootScope.responseError(response);
                });
        };

        /**
         * For when item is deleted from the item popup
         */
        $scope.closeItemPopup = function () {
            if ($scope.show.popups.item) {
                $scope.show.popups.item = false;
                $scope.itemPopup = {};
            }
        };

        $scope.deleteJsItem = function ($parent, $item) {
            if ($parent) {
                $parent.children = _.without($parent.children, $item);
            }
            else {
                $scope.items = _.without($scope.items, $item);
            }
        };

        var $parent;
        $scope.findParent = function($array, $item) {
            if (!$item.parent_id) {
                return false;
            }
            $($array).each(function () {
                if (this.id === $item.parent_id) {
                    $parent = this;
                    return false;
                }
                if (this.children) {
                    $scope.findParent(this.children, $item);
                }
            });
            return $parent;
        };

        $scope.moveUp = function ($item, $index) {
            $scope.items.splice($index, 1);
            $scope.items.splice($index - 1, 0, $item);
        };

        $scope.updateItem = function () {
            $rootScope.showLoading();
            ItemsFactory.updateItem($scope.itemPopup)
                .then(function (response) {
                    $scope.jsUpdateItem(response);
                    $scope.show.popups.item = false;
                    $scope.provideFeedback('Item updated');
                    $scope.toggleFavourite();
                    $scope.itemPopup = {};
                    $rootScope.hideLoading();
                })
                .catch(function (response) {
                    $rootScope.responseError(response);
                });
        };

        $scope.jsUpdateItem = function (response) {
            var $parent = SortableFactory.findParent($scope.items, $scope.itemPopup);
            if ($parent) {
                var $index = _.indexOf($parent.children, _.findWhere($parent.children, {id: $scope.itemPopup.id}));
                $parent.children[$index] = response.data;
            }
            else {
                var $index = _.indexOf($scope.items, _.findWhere($scope.items, {id: $scope.itemPopup.id}));
                $scope.items[$index] = response.data;
            }
        };

        /**
         * For when the 'favourite' button in the item popup is toggled,
         * after the item is saved
         */
        $scope.toggleFavourite = function () {
            var $itemInFavourites = _.findWhere($scope.favourites, {id: $scope.itemPopup.id});
            //Remove the item from the $scope.favourites if it is no longer a favourite
            if ($itemInFavourites && !$scope.itemPopup.favourite) {
                $scope.favourites = _.without($scope.favourites, $itemInFavourites);
            }
            //Add the item to $scope.favourites if it is now a favourite
            else if (!$itemInFavourites && $scope.itemPopup.favourite) {
                //Todo: put the item in the correct place rather than at the end
                $scope.favourites.push($scope.itemPopup);
            }
        };

        $scope.clearNewItemFields = function () {
            $scope.new_item.title = '';
            $scope.new_item.body = '';
        };

        $scope.undoDeleteItem = function () {
            $rootScope.showLoading();
            ItemsFactory.undoDeleteItem()
                .then(function (response) {
                    $scope.jsRestoreItem(response.data);
                    $scope.provideFeedback('Item restored');
                    $rootScope.hideLoading();
                })
                .catch(function (response) {
                    $rootScope.responseError(response);
                });
        };

        /**
         * After undoing delete item, restored item is returned in the response.
         * Add this item to the items with the JS.
         * @param $item
         */
        $scope.jsRestoreItem = function ($item) {
            if (!$item.parent_id) {
                //Restore the item back home.
                if (!$scope.breadcrumb || $scope.breadcrumb.length < 1) {
                    //We are home
                    $scope.items.push($item);
                }

            }
            else {
                var $parent = SortableFactory.findParentById($item, $scope.items);
                if ($parent) {
                    //Todo: put it in the right spot, not just at the end
                    $parent.children.push($item);
                }
            }
        };

        /**
         * $short_path is an array of indexes to the item, for example:
         * [0,2,1]
         * Duplicate from sortable directive
         */
        //$scope.findParentByPath = function ($item) {
        //    return SortableFactory.findParentByPath($item, $scope.items);
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

        $scope.showItemPopup = function ($item) {
            $scope.show.popups.item = true;
            $scope.itemPopup = $item;
        };

        //$("body").on('click', function (event) {
        //    if (!$("#favourites")[0].contains(event.target)) {
        //        $scope.show.favourites = false;
        //    }
        //});

    }); //end controller

})();