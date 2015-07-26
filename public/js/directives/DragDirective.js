;(function(){
    'use strict';
    angular
        .module('lists')
        .directive('dragDirective', drag);

    /* @inject */
    function drag($document, ListsFactory, DragFactory, FeedbackFactory) {
        return {
            restrict: 'EA',
            scope: {
                item: '=something',
                items: '=items',
                //newIndex: '=newindex'
            },
            //replace: true,
            templateUrl: 'templates/DragTemplate.php',
            //scope: true,
            link: function($scope, elem, attrs) {
                var $guide = $(elem).closest('.item').prev('.guide');
                //$scope.newIndex = $scope.item.index;
                $scope.dragFactory = DragFactory;

                $scope.$watch('dragFactory.newIndex', function (newValue) {
                    $scope.newIndex = newValue;
                });

                $scope.$watch('dragFactory.newParent', function (newValue) {
                    $scope.newParent = newValue;
                });

                elem.on('mousedown', function (event) {
                    event.preventDefault();
                    $document.on('mouseup', mouseup);
                });

                $scope.mouseOver = function ($item, $event) {
                    $($event.target).addClass('highlight');
                    DragFactory.setNewIndex($item.index);
                    DragFactory.setNewParent($scope.findParentByPath($item));
                };

                /**
                 * $short_path is an array of indexes to the item, for example:
                 * [0,2,1]
                 */
                $scope.findParentByPath = function ($item) {
                    if (!$item.parent_id) {
                        return false;
                    }
                    var $short_path = $item.path_to_item;
                    var $item = $scope.items[$short_path[0]];
                    $item = DragFactory.findParentByPath($item, $short_path);
                    return $item;
                };

                $scope.mouseLeave = function ($item, $event) {
                    $($event.target).removeClass('highlight');
                };

                $scope.findParent = function() {
                    return DragFactory.findParent($scope.items, $scope.item);
                };

                /**
                 * Get an array that include the items siblings as well as the item itself
                 * @returns {*}
                 */
                $scope.findSiblingsWithItem = function () {
                    return DragFactory.findSiblingsWithItem($scope.items, $scope.item);
                };

                $scope.parent = $scope.findParent();
                $scope.siblingsWithItem = $scope.findSiblingsWithItem();

                /**
                 * Update the index properties of the items with the JS.
                 * $siblings includes the item itself.
                 */
                $scope.updateJs = function ($new_parent) {
                    var $siblings;

                    if (!$new_parent) {
                        $siblings = $scope.jsMoveItemSameParent();
                        $scope.updateJsIndexes($siblings);
                    }
                    else {
                        $siblings = $scope.jsMoveToNewParent();
                        $scope.updateJsIndexes($siblings);
                    }
                };

                $scope.updateJsIndexes = function ($siblings) {
                    for (var i = 0; i < $siblings.length; i++) {
                        $siblings[i].index = i;
                    }
                };

                //$scope.jsMoveItemOut = function ($parent) {
                //    $parent.children.splice($scope.item.index, 1);
                //    return $parent.children;
                //};

                $scope.jsMoveToNewParent = function () {
                    var $parent = $scope.findParent();
                    var $siblings;

                    if ($parent) {
                        $parent.children.splice($scope.item.index, 1);
                        $siblings = $parent.children;
                        $scope.newParent.children.push($scope.item);
                    }
                    else {
                        //The item is home (no parent)
                        $scope.items.splice($scope.item.index, 1);
                        $siblings = $scope.items;
                    }

                    return $siblings;
                };

                /**
                 * Move an item, keeping the same parent
                 * @returns {*}
                 */
                $scope.jsMoveItemSameParent = function () {
                    var $parent = $scope.findParent();
                    var $siblings;

                    if ($parent) {
                        $parent.children.splice($scope.item.index, 1);
                        $parent.children.splice($scope.newIndex, 0, $scope.item);
                        $siblings = $parent.children;
                    }
                    else {
                        //The item is home (no parent)
                        $scope.items.splice($scope.item.index, 1);
                        $scope.items.splice($scope.newIndex, 0, $scope.item);
                        $siblings = $scope.items;
                    }

                    return $siblings;
                };

                function provideFeedback ($message) {
                    FeedbackFactory.provideFeedback($message);
                };

                $scope.moveItemSameParent = function () {
                    ListsFactory.moveItemSameParent($scope.item, $scope.newIndex)
                        .then(function (response) {
                            provideFeedback('Item moved');
                        })
                        .catch(function (response) {
                            provideFeedback('There was an error');
                        });
                    $scope.updateJs(false);
                    $scope.$apply();
                };

                $scope.moveToNewParent = function () {
                    ListsFactory.moveToNewParent($scope.item, $scope.newParent)
                        .then(function (response) {
                            provideFeedback('Item moved');
                        })
                        .catch(function (response) {
                            provideFeedback('There was an error');
                        });
                    $scope.updateJs(true);
                    $scope.$apply();
                };

                function mouseup (event) {
                    $document.off('mouseup', mouseup);
                    //Todo: Fix this if/else if-$scope.newParent.id is false and therefore does not equal $scope.item.parent_id when it should.
                    if ($scope.newIndex !== $scope.item.index && $scope.newParent.id === $scope.item.parent_id) {
                        $scope.moveItemSameParent();
                    }
                    else if ($scope.newParent.id !== $scope.item.parent_id) {
                        $scope.moveToNewParent();
                    }
                }

            }
        };
    }
}).call(this);

