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

                $scope.$watch('dragFactory.newParentId', function (newValue) {
                    $scope.newParentId = newValue;
                });

                elem.on('mousedown', function (event) {
                    event.preventDefault();
                    $document.on('mouseup', mouseup);
                });

                $scope.mouseOver = function ($item, $event) {
                    $($event.target).addClass('highlight');
                    DragFactory.setNewIndex($item.index);
                    DragFactory.setNewParentId($item.parent_id);
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
                $scope.updateJsIndexes = function () {
                    var $siblings = $scope.jsMoveItem();

                    for (var i = 0; i < $siblings.length; i++) {
                        $siblings[i].index = i;
                    }
                };

                $scope.jsMoveItem = function () {
                    var $parent = $scope.findParent();
                    var $siblings;

                    if ($parent) {
                        $parent.children.splice($scope.item.index, 1);
                        $parent.children.splice($scope.newIndex, 0, $scope.item);
                        $siblings = $parent.children;
                    }
                    else {
                        $scope.items.splice($scope.item.index, 1);
                        $scope.items.splice($scope.newIndex, 0, $scope.item);
                        $siblings = $scope.items;
                    }

                    return $siblings;
                };

                //$scope.getPathToItem = function () {
                //    var $short_path = $scope.item.path_to_item;
                //    var $long_path;
                //
                //    var $oldest_ancestor = $scope.items[$short_path[0]];
                //
                //    $scope.items[$short_path[0]].title = 'hi';
                //
                //    //$long_path = $oldest_ancestor.children[$short_path[1]];
                //};

                //$scope.getPathToItem();

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
                    $scope.updateJsIndexes();
                    $scope.$apply();
                };

                $scope.moveToNewParent = function () {
                    ListsFactory.moveToNewParent($scope.item, $scope.newParentId)
                        .then(function (response) {
                            provideFeedback('Item moved');
                        })
                        .catch(function (response) {
                            provideFeedback('There was an error');
                        });
                    //$scope.updateJsIndexes();
                    //$scope.$apply();
                };

                function mouseup (event) {
                    $document.off('mouseup', mouseup);

                    if ($scope.newIndex !== $scope.item.index && $scope.newParentId === $scope.item.parent_id) {
                        $scope.moveItemSameParent();
                    }
                    else if ($scope.newParentId !== $scope.item.parent_id) {
                        $scope.moveToNewParent();
                    }
                }

            }
        };
    }
}).call(this);

