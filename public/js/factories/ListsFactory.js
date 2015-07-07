app.factory('ListsFactory', function ($http) {
    return {

        /**
         * select
         */
        
        getChildren: function ($item) {
            var $url = $item.path;

            return $http.get($url);
        },
        filter: function () {
            var $url = 'filter';
            var $typing = $("#filter").val();
            var $data = {
                typing: $typing
            };

            return $http.post($url, $data);
        }

        /**
         * insert
         */

        /**
         * update
         */

        /**
         * delete
         */
    };
});
