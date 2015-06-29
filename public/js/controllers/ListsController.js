var app = angular.module('lists');

(function () {
    app.controller('ListsController', function ($scope, $http, ListsFactory) {

        /**
         * scope properties
         */

        /**
         * watches
         */

        /**
         * select
         */

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