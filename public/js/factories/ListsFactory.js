app.factory('ListsFactory', function ($http) {
    return {

        /**
         * select
         */
        
        getChildren: function ($item) {
            var $url = $item.path;

            return $http.get($url);
        },
        goHome: function () {
            return $http.get('/items');
        },
        filter: function ($typing) {
            var $url = 'filter';
            var $data = {
                typing: $typing
            };

            return $http.post($url, $data);
        },

        /**
         * insert
         */

        insertItem: function ($zoomed_item, $new_item) {
            if ($zoomed_item) {
                $parent_id = $zoomed_item.id;
            }
            else {
                $parent_id = null;
            }
            var $url = '/items';
            var $data = {
                parent_id: $parent_id,
                new_item: $new_item
            };

            return $http.post($url, $data);
        },

        /**
         * update
         */

        /**
         * delete
         */

        deleteItem: function ($item) {
            var $url = $item.path;

            return $http.delete($url);
        }
    };
});
