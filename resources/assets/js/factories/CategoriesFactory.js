app.factory('CategoriesFactory', function ($http) {
    return {
        insert: function () {
            var $url = '/api/categories';
            var $data = {
                name: $("#new-category").val()
            };

            $("#new-category").val("");

            return $http.post($url, $data);
        }
    };
});
