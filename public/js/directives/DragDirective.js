;(function(){
    'use strict';
    angular
        .module('lists')
        .directive('dragDirective', drag);

    /* @inject */
    function drag($document, ListsFactory, DragFactory) {
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

                elem.on('mousedown', function (event) {
                    event.preventDefault();
                    //$scope.originalX = event.pageX;
                    //$scope.originalY = event.pageY;
                    //$scope.positionsMoved = 0;
                    //$scope.guidePosition = $scope.getItemIndex();
                    $document.on('mousemove', mousemove);
                    $document.on('mouseup', mouseup);
                });

                //elem.on('mouseover', function () {
                //    $(this).addClass('highlight');
                //}).on('mouseleave', function () {
                //    $(this).removeClass('highlight');
                //});

                //$("body").on('mouseover', '.item-content', function (event) {
                //    $scope.mouseOver(this, event);
                //});

                $scope.mouseOver = function ($item, $event) {
                    $($event.target).addClass('highlight');
                    DragFactory.setNewIndex($item.index);
                    //$scope.$apply();
                };

                $scope.mouseLeave = function ($item, $event) {
                    $($event.target).removeClass('highlight');
                };

                //$scope.getItemIndex = function () {
                //    if ($scope.parent) {
                //        $scope.index = $scope.parent.indexOf($scope.item);
                //    }
                //    else {
                //        $scope.index = $scope.items.indexOf($scope.item);
                //    }
                //    return $scope.index;
                //};

                $scope.findParent = function($array, $item) {
                    return DragFactory.findParent($array, $item);
                };

                $scope.parent = $scope.findParent($scope.items, $scope.item);

                $scope.moveItem = function () {
                    //$scope.getItemIndex();
                    $scope.items.splice($scope.item.index, 1);
                    $scope.items.splice($scope.newIndex, 0, $scope.item);
                    ListsFactory.updateIndex($scope.item, $scope.newIndex)
                        .then(function (response) {
                            //Todo: update the indexes in the JS.
                            //Todo: Don't need to wait for the response for this.
                            //Todo: Make moving item down work, too.
                        })
                        .catch(function (response) {
                            //Todo: provide error feedback
                        });
                    $scope.$apply();
                };

                function mousemove (event) {
                    //$scope.diffX = event.pageX - $scope.originalX;
                    //$scope.diffY = event.pageY - $scope.originalY;
                    //var $pxToMoveGuide;
                    //var $li = $(elem).closest('.item-with-children').children();
                    //
                    //var $prevHeight = parseInt($($li).eq($scope.guidePosition - 1).css('height'));
                    //var $nextHeight = parseInt($($li).eq($scope.guidePosition + 1).css('height'));
                    //var $thisHeight = parseInt($(elem).css('height'));
                    //
                    //if ($scope.diffY >= $nextHeight) {
                    //    $scope.positionsMoved = 1;
                    //    $pxToMoveGuide = $nextHeight + $thisHeight;
                    //}
                    //else if ($scope.diffY <= ($prevHeight * -1)) {
                    //    $scope.positionsMoved = -1;
                    //    $pxToMoveGuide = -$prevHeight;
                    //}
                    //
                    //if ($scope.positionsMoved !== 0) {
                    //    $scope.moveGuide($pxToMoveGuide);
                    //}
                }

                $scope.moveGuide = function ($pxToMoveGuide) {
                    //$($guide).css({
                    //    display: 'block',
                    //    position: 'relative',
                    //    top: $pxToMoveGuide + 'px'
                    //});
                };

                $scope.hideGuide = function () {
                    //$($guide).css({
                    //    display: 'none'
                    //});
                };

                function mouseup (event) {
                    $document.off('mousemove', mousemove);
                    $document.off('mouseup', mouseup);
                    $scope.moveItem();
                    $scope.hideGuide();
                }

            }
        };
    }
}).call(this);

