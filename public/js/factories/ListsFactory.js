app.factory('ListsFactory', function ($http) {
    return {

        /**
         * select
         */
        
        getChildren: function ($item) {
            var $url = $item.path;

            return $http.get($url);
        },

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
