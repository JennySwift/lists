app.factory('ItemsFactory', function ($http) {
    return {

        /**
         * select
         */
        
        getChildren: function ($item) {
            var $url = $item.path;

            return $http.get($url);
        },
        goHome: function () {
            return $http.get('/api/items');
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
            var $url = '/api/items';
            var $data = {
                parent_id: $parent_id,
                new_item: $new_item
            };

            return $http.post($url, $data);
        },

        /**
         * update
         */

        updateItem: function ($item) {
            var $url = 'updateItem';
            var $data = {
                item: $item
            };

            return $http.post($url, $data);
        },

        moveItemSameParent: function ($item, $new_index) {
            var $url = $item.path;
            var $data = {
                old_index: $item.index,
                new_index: $new_index,
                parent_id: $item.parent_id
            };

            return $http.put($url, $data);
        },

        moveToNewParent: function ($item, $new_parent, $new_index) {
            var $url = $item.path;
            var $data = {
                old_index: $item.index,
                old_parent_id: $item.parent_id,
                new_parent_id: $new_parent.id,
                new_parent: true,
                new_index: $new_index
            };

            return $http.put($url, $data);
        },

        undoDeleteItem: function () {
            var $url = 'undoDeleteItem';

            return $http.put($url);
        },

        /**
         * delete
         */

        deleteItem: function ($item) {
            var $url = $item.path;

            return $http.delete($url);
        }
    };
});
