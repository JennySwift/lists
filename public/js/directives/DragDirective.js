;(function(){
    'use strict';
    angular
        .module('lists')
        .directive('dragDirective', drag);

    /* @inject */
    function drag($document) {
        return {
            restrict: 'EA',
            scope: {
                item: '=something'
            },
            //templateUrl: 'templates/DropdownsTemplate.php',
            //scope: true,
            link: function($scope, elem, attrs) {
                var $originalX = 0, $originalY = 0, $diffX = 0, $diffY = 0;
                var $item = attrs.itemAttr;

                elem.css({
                    position: 'relative',
                    cursor: 'pointer'
                });

                elem.on('mousedown', function (event) {
                    event.preventDefault();
                    $originalX = event.pageX - $diffX;
                    $originalY = event.pageY - $diffY;
                    $document.on('mousemove', mousemove);
                    $document.on('mouseup', mouseup);
                });

                function mousemove (event) {
                    $diffX = event.pageX - $originalX;
                    $diffY = event.pageY - $originalY;

                    if ($scope.item.children) {
                        elem.parent().css({
                            position: 'relative',
                            top: $diffY + 'px',
                        });
                    }
                    else {
                        elem.css({
                            top: $diffY + 'px',
                            //left: $diffX + 'px'
                        });
                    }
                }

                function mouseup (event) {
                    $document.off('mousemove', mousemove);
                    $document.off('mouseup', mouseup);
                }

            }
        };
    }
}).call(this);

