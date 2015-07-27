;(function(){
    'use strict';
    angular
        .module('lists')
        .directive('sortableDirective', drag);

    /* @inject */
    function drag($document, ListsFactory, SortableFactory, FeedbackFactory) {
        return {
            restrict: 'EA',
            scope: {
                item: '=something',
                items: '=items',
                //newIndex: '=newindex'
            },
            //replace: true,
            templateUrl: 'sortable',
            //scope: true,
            link: function($scope, elem, attrs) {
                var $guide = $(elem).closest('.item').prev('.guide');
                var $mouseDown = false;
                //$scope.newIndex = $scope.item.index;
                $scope.sortableFactory = SortableFactory;

                $scope.$watch('sortableFactory.newIndex', function (newValue) {
                    $scope.newIndex = newValue;
                });

                $scope.$watch('sortableFactory.newParent', function (newValue) {
                    $scope.newParent = newValue;
                });

                $scope.$watch('sortableFactory.mouseDown', function (newValue) {
                    $mouseDown = newValue;
                });

                $scope.$watch('sortableFactory.newTarget', function (newValue) {
                    $scope.newTarget = newValue;

                    if ($mouseDown && $scope.selectedItem) {
                        $scope.showGuide();
                    }
                    else if (!$mouseDown) {
                        $($scope.newTarget).addClass('highlight');
                    }
                });

                $scope.mouseLeave = function ($item, $event) {
                    if (!$mouseDown) {
                        $($event.target).removeClass('highlight');
                    }
                    $($event.target).removeClass('top-guide bottom-guide');
                };

                $scope.showGuide = function () {
                    //If the target has the same parent and the item is being moved up,
                    //show the guide above the target
                    if ($scope.item.index > $scope.newIndex && $scope.item.parent_id == $scope.newParent.id) {
                        $($scope.newTarget).addClass('top-guide');
                    }
                    else if ($scope.item.index < $scope.newIndex && $scope.item.parent_id == $scope.newParent.id) {
                        $($scope.newTarget).addClass('bottom-guide');
                    }
                    else if ($scope.item.parent_id != $scope.newParent.id) {
                        //New parent
                        $($scope.newTarget).addClass('top-guide');
                    }
                };

                elem.on('mousedown', function (event) {
                    event.preventDefault();
                    SortableFactory.setMouseDown(true);
                    //$mouseDown = true;
                    $document.on('mouseup', mouseup);
                    $(event.target).addClass('highlight');
                    $scope.selectedItem = $scope.item;
                });

                $scope.mouseOver = function ($item, $event) {
                    SortableFactory.setNewTarget($event.target);
                    SortableFactory.setNewIndex($item.index);
                    SortableFactory.setNewParent($scope.findParentByPath($item));
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
                    $item = SortableFactory.findParentByPath($item, $short_path);
                    return $item;
                };

                $scope.findParent = function() {
                    return SortableFactory.findParent($scope.items, $scope.item);
                };

                /**
                 * Get an array that include the items siblings as well as the item itself
                 * @returns {*}
                 */
                $scope.findSiblingsWithItem = function () {
                    return SortableFactory.findSiblingsWithItem($scope.items, $scope.item);
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
                        $scope.updateJsIndexes($new_siblings);
                        $scope.item.parent_id = $scope.newParent.id;
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

                var $siblings;
                var $new_siblings;
                $scope.jsMoveToNewParent = function () {
                    var $parent = $scope.findParent();

                    if ($parent) {
                        $parent.children.splice($scope.item.index, 1);
                        $siblings = $parent.children;
                        if ($scope.newParent) {
                            //$scope.newParent.children.push($scope.item);
                            $scope.newParent.children.splice($scope.newIndex, 0, $scope.item);
                            $new_siblings = $scope.newParent.children;
                        }
                        else {
                            //The item is being move home (no new parent)
                            //$scope.items.push($scope.item);
                            $scope.items.splice($scope.newIndex, 0, $scope.item);
                            $new_siblings = $scope.items;
                        }

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
                    ListsFactory.moveToNewParent($scope.item, $scope.newParent, $scope.newIndex)
                        .then(function (response) {
                            provideFeedback('Item moved');
                        })
                        .catch(function (response) {
                            provideFeedback('There was an error');
                        });
                    $scope.updateJs(true);
                    $scope.$apply();
                };

                $scope.removeClasses = function () {
                    $(".top-guide").removeClass('top-guide');
                    $(".bottom-guide").removeClass('bottom-guide');
                    $(".highlight").removeClass('highlight');
                };

                function mouseup (event) {
                    $document.off('mouseup', mouseup);
                    SortableFactory.setMouseDown(false);
                    //$mouseDown = false;
                    if ($scope.newIndex !== $scope.item.index && $scope.newParent.id == $scope.item.parent_id) {
                        $scope.moveItemSameParent();
                    }
                    else if ($scope.newParent.id != $scope.item.parent_id) {
                        $scope.moveToNewParent();
                    }

                    $scope.removeClasses();
                    $scope.selectedItem = false;
                }

            }
        };
    }
}).call(this);

