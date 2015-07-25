;(function(){
    'use strict';
    angular
        .module('lists')
        .directive('dragDirective', drag);

    /* @inject */
    function drag($document, ListsFactory) {
        return {
            restrict: 'EA',
            scope: {
                item: '=something',
                items: '=items'
            },
            //templateUrl: 'templates/DragTemplate.php',
            //scope: true,
            link: function($scope, elem, attrs) {
                var $guide = $(elem).closest('.item').prev('.guide');

                elem.on('mousedown', function (event) {
                    event.preventDefault();
                    $scope.originalX = event.pageX;
                    $scope.originalY = event.pageY;
                    $scope.positionsMoved = 0;
                    $scope.guidePosition = $scope.getItemIndex();
                    $document.on('mousemove', mousemove);
                    $document.on('mouseup', mouseup);
                });

                $scope.getItemIndex = function () {
                    if ($scope.parent) {
                        $scope.index = $scope.parent.indexOf($scope.item);
                    }
                    else {
                        $scope.index = $scope.items.indexOf($scope.item);
                    }
                    return $scope.index;
                };

                $scope.moveGuide = function ($pxToMoveGuide) {
                    $($guide).css({
                        display: 'block',
                        position: 'relative',
                        top: $pxToMoveGuide + 'px'
                    });
                };

                $scope.moveItem = function () {
                    $scope.getItemIndex();
                    $scope.items.splice($scope.index, 1);
                    $scope.items.splice($scope.index + $scope.positionsMoved, 0, $scope.item);
                    $scope.$apply();
                };

                $scope.$watch('items', function (newValue, oldValue) {
                    console.log(newValue);
                });

                $scope.hideGuide = function () {
                    $($guide).css({
                        display: 'none'
                    });
                };

                function mousemove (event) {
                    $scope.diffX = event.pageX - $scope.originalX;
                    $scope.diffY = event.pageY - $scope.originalY;
                    var $pxToMoveGuide;
                    var $li = $(elem).closest('.item-with-children').children();


                    var $prevHeight = parseInt($($li).eq($scope.guidePosition - 1).css('height'));
                    var $nextHeight = parseInt($($li).eq($scope.guidePosition + 1).css('height'));
                    var $thisHeight = parseInt($(elem).css('height'));

                    if ($scope.diffY >= $nextHeight) {
                        $scope.positionsMoved = 1;
                        $pxToMoveGuide = $nextHeight + $thisHeight;
                    }
                    else if ($scope.diffY <= ($prevHeight * -1)) {
                        $scope.positionsMoved = -1;
                        $pxToMoveGuide = -$prevHeight;
                    }
                    //console.log('diffY: ' + $scope.diffY);
                    //console.log('prevHeight: ' + $prevHeight);
                    //console.log('nextHeight: ' + $nextHeight);
                    //console.log('scope.guidePosition: ' + $scope.guidePosition);
                    console.log($(elem).siblings());

                    //if ($diffY > 0) {
                    //    $scope.positionsMoved = Math.floor($diffY / 33);
                    //}
                    //else {
                    //    $scope.positionsMoved = Math.ceil($diffY / 33);
                    //}

                    //var $moveGuide = $scope.positionsMoved * 33;

                    //console.log('diffY: ' + $diffY);
                    //console.log('positionsMoved: ' + $scope.positionsMoved);
                    //console.log('moveGuide: ' + $moveGuide);

                    if ($scope.positionsMoved !== 0) {
                        $scope.moveGuide($pxToMoveGuide);
                    }
                }

                $scope.findParent = function($array, $item) {
                    var $parent;
                    if (!$item.parent_id) {
                        return false;
                    }
                    $($array).each(function () {
                        if (this.id === $item.parent_id) {
                            return $parent = this;
                        }
                        if (this.children) {
                            $scope.findParent(this.children, $item);
                        }
                    });
                    return $parent;
                };

                $scope.parent = $scope.findParent($scope.items, $scope.item);

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

