var app = angular.module('lists');

(function () {
    app.controller('CategoriesController', function ($scope, $http, FeedbackFactory, CategoriesFactory) {

        $scope.categories = categories;

        $scope.createCategory = function ($keycode) {
            if ($keycode !== 13) {
                return false;
            }
            //$scope.showLoading();
            CategoriesFactory.insert()
                .then(function (response) {
                    $scope.categories = response.data;
                    //$scope.provideFeedback('');
                    //$scope.hideLoading();
                })
                .catch(function (response) {
                    //$scope.responseError(response);
                });
        };

    }); //end controller

})();