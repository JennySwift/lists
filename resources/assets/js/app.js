var app = angular.module('lists', [
    'ngSanitize',
    'checklist-model'
]);

app.config(function ($interpolateProvider) {
    $interpolateProvider.startSymbol('[[');
    $interpolateProvider.endSymbol(']]');
});

app.run(runBlock);

function runBlock ($rootScope, ErrorsFactory) {

    $rootScope.show = {
        popups: {}
    };

    $rootScope.responseError = function (response) {
        $rootScope.$broadcast('provideFeedback', ErrorsFactory.responseError(response), 'error');
        $rootScope.hideLoading();
    };

    $rootScope.closePopup = function ($event, $popup) {
        var $target = $event.target;
        if ($target.className === 'popup-outer') {
            $rootScope.show.popups[$popup] = false;
        }
    };

    $rootScope.showLoading = function () {
        $rootScope.loading = true;
    };

    $rootScope.hideLoading = function () {
        $rootScope.loading = false;
    };
}
