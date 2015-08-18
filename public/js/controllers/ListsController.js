var app = angular.module('lists');

(function () {
    app.controller('ListsController', function ($scope, $http, ListsFactory, FeedbackFactory, SortableFactory) {

        /**
         * scope properties
         */

        $scope.items = items;
        $scope.categories = categories;
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
            }
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

        function provideFeedback ($message) {
            FeedbackFactory.provideFeedback($message);
        };

        $scope.provideFeedback = function ($message) {
            $scope.feedback_messages.push($message);
            setTimeout(function () {
                $scope.feedback_messages = _.without($scope.feedback_messages, $message);
                $scope.$apply();
            }, 3000);
        };


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

        $scope.insertItem = function ($keycode) {
            if ($keycode !== 13) {
                return false;
            }

            ListsFactory.insertItem($scope.zoomed_item, $scope.new_item)
                .then(function (response) {
                    if ($scope.zoomed_item) {
                        $scope.showChildren(response, $scope.zoomed_item);
                    }
                    else {
                        $scope.showHome(response);
                    }
                    $scope.clearNewItemFields();
                    provideFeedback('Item added');
                })
                .catch(function (response) {
                    provideFeedback('There was an error');
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
            //var $parent = $scope.findParent($scope.items, $item);
            //console.log($parent);
            ListsFactory.deleteItem($item)
                .then(function (response) {
                    var $parent = $scope.findParent($scope.items, $item);
                    console.log($parent);
                    console.log($parent.children);
                    $scope.deleteJsItem($parent, $item);

                })
                .catch(function (response) {

                });
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

        //$scope.deleteJsItem = function () {
        //
        //};

        $scope.updateItem = function () {
            //$scope.showLoading();
            ListsFactory.updateItem($scope.itemPopup)
                .then(function (response) {
                    var $parent = $scope.findParentByPath($scope.itemPopup);
                    if ($parent) {
                        $parent.children[$scope.itemPopup.index] = response.data;
                    }
                    else {
                        $scope.items[$scope.itemPopup.path_to_item] = response.data;
                    }

                    $scope.provideFeedback('Category updated');
                    //$scope.hideLoading();
                })
                .catch(function (response) {
                    //$scope.responseError(response);
                    $scope.provideFeedback('error');
                });
        };

        $scope.clearNewItemFields = function () {
            $scope.new_item.title = '';
            $scope.new_item.body = '';
        };

        /**
         * $short_path is an array of indexes to the item, for example:
         * [0,2,1]
         * Duplicate from sortable directive
         */
        $scope.findParentByPath = function ($item) {
            if (!$item.parent_id) {
                return false;
            }
            var $short_path = $item.path_to_item;
            var $item = $scope.items[$short_path[0]];
            $item = SortableFactory.findParentByPath($item, $short_path);
            return $item;
        };

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


















    //$scope.mouseDown = function (event, $item) {
    //    event.preventDefault();
    //    $scope.item = $item;
    //    $scope.parent = $scope.findParentForMove($scope.items, $scope.item);
    //    $scope.mouseMoveListen = true;
    //    $scope.originalX = event.pageX;
    //    $scope.originalY = event.pageY;
    //    $scope.positionsMoved = 0;
    //    $scope.guidePosition = $scope.getItemIndex();
    //};
    //
    //    //$scope.something = function (e) {
    //    //    $("body").on('mousemove', function (e) {
    //    //        console.log($event);
    //    //    });
    //    //};
    //
    //    $scope.getItemIndex = function () {
    //        if ($scope.parent) {
    //            $scope.index = $scope.parent.indexOf($scope.item);
    //        }
    //        else {
    //            $scope.index = $scope.items.indexOf($scope.item);
    //        }
    //        return $scope.index;
    //    };
    //
    //    $scope.moveGuide = function ($pxToMoveGuide) {
    //        $($scope.elem).prev('.guide').css({
    //            display: 'block',
    //            position: 'relative',
    //            top: $pxToMoveGuide + 'px'
    //        });
    //    };
    //
    //    $scope.moveItem = function () {
    //        $scope.getItemIndex();
    //        $scope.items.splice($scope.index, 1);
    //        $scope.items.splice($scope.index + $scope.positionsMoved, 0, $scope.item);
    //        //$scope.$apply();
    //    };
    //
    //    $scope.hideGuide = function () {
    //        $($scope.elem).prev('.guide').css({
    //            display: 'none'
    //        });
    //    };
    //
    //    $scope.mouseMove = function (event) {
    //        if (!$scope.mouseMoveListen) {
    //            return false;
    //        }
    //        $scope.diffX = event.pageX - $scope.originalX;
    //        $scope.diffY = event.pageY - $scope.originalY;
    //        $scope.elem = event.target;
    //        var $pxToMoveGuide;
    //
    //
    //        var $prevHeight = parseInt($($scope.elem).parent().children().eq($scope.guidePosition - 1).css('height'));
    //        var $nextHeight = parseInt($($scope.elem).parent().children().eq(2).css('height'));
    //        var $thisHeight = parseInt($($scope.elem).css('height'));
    //
    //        if ($scope.diffY >= $nextHeight) {
    //            $scope.positionsMoved = 1;
    //            $pxToMoveGuide = $nextHeight + $thisHeight;
    //        }
    //        else if ($scope.diffY <= ($prevHeight * -1)) {
    //            $scope.positionsMoved = -1;
    //            $pxToMoveGuide = -$prevHeight;
    //        }
    //        //console.log('diffY: ' + $scope.diffY);
    //        //console.log('prevHeight: ' + $prevHeight);
    //        //console.log('nextHeight: ' + $nextHeight);
    //        //console.log('scope.guidePosition: ' + $scope.guidePosition);
    //
    //        //if ($diffY > 0) {
    //        //    $scope.positionsMoved = Math.floor($diffY / 33);
    //        //}
    //        //else {
    //        //    $scope.positionsMoved = Math.ceil($diffY / 33);
    //        //}
    //
    //        //var $moveGuide = $scope.positionsMoved * 33;
    //
    //        //console.log('diffY: ' + $diffY);
    //        //console.log('positionsMoved: ' + $scope.positionsMoved);
    //        //console.log('moveGuide: ' + $moveGuide);
    //
    //        if ($scope.positionsMoved !== 0) {
    //            $scope.moveGuide($pxToMoveGuide);
    //        }
    //    };
    //
    //    $scope.findParentForMove = function($array, $item) {
    //        var $parent;
    //        if (!$item.parent_id) {
    //            return false;
    //        }
    //        $($array).each(function () {
    //            if (this.id === $item.parent_id) {
    //                this.children = _.without(this.children, $item);
    //                return $parent = this;
    //            }
    //            if (this.children) {
    //                $scope.findParentForMove(this.children, $item);
    //            }
    //        });
    //        return $parent;
    //    };
    //
    //    $scope.mouseUp = function () {
    //        //$("document").off('mousemove', $scope.mouseMove());
    //        //$("document").off('mouseup', $scope.mouseUp());
    //        $scope.mouseMoveListen = false;
    //        $scope.moveItem();
    //        $scope.hideGuide();
    //    };
    //
    //    $("document").on('mouseup', $scope.mouseUp());

    }); //end controller

})();