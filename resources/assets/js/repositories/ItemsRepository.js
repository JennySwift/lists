var ItemsRepository = {

    initialData: {
        showLoading: false,
        showItemPopup: false,
        showFavourites: false,
        itemPopup: {},
        items: [],
        zoomedItem: {},
        pinnedItems: [],
        breadcrumb: [],
        addingNewItems: false,
        editingItems: false,
        //selectedItems: {}
        categories: categories,
        favouriteItems: [],
        paths: {
            base: base_path,
            test: base_path + '/resources/views/test.php'
        },
        newItem: {
            title: '',
            body: '',
            favourite: false,
            pinned: false
        },
        newIndex: -1,
        filterPriority: '',
        filterCategory: '',
        filterTitle: ''
    },

    /**
     *
     * @param item
     * @returns {{title: *, body: (*|string|Array|HTMLElement), priority: *, favourite: (*|boolean), pinned: (*|boolean), category_id: *, parent_id: (*|null)}}
     */
    setData: function (item, zoomedItem) {
        var data = {
            title: item.title,
            body: item.body,
            priority: item.priority,
            favourite: item.favourite,
            pinned: item.pinned,
            category_id: item.category.id
        };

        if (zoomedItem) {
            data.parent_id = zoomedItem.id;
        }
        else {
            data.parent_id = null;
        }

        return data;
    },

    //This should be uncommented but I commented it during switch to Vue
    //var $parent;

    /**
     * This works. It seems kind of complicated, but I tried other ways
     * and they both had problems.
     *
     * Finding parent by path broke down when zoomed on an item, because path was not the full path.
     *
     * Finding parent with _.flatten broke down when not zoomed on an item
     * and items were expanded several levels.
     *
     * @param array
     * @param item
     * @returns {*}
     */
    findParent: function (array, item) {
        var parent;
        if (!item.parent_id) {
            return false;
        }
        $(array).each(function () {
            if (this.id === item.parent_id) {
                parent = this;
                return false;
            }
            if (this.children) {
                findParent(this.children, item);
            }
        });

        return parent;
    },

    /**
     * If url is /items/:2, return 2
     * @param that
     * @returns {*}
     */
    getIdFromUrl: function (that) {
        //For some reason $route.params was undefined.
        //if (that.$route.params.id) {
        //    return that.$route.params.id.slice(1);
        //}
        var path = that.$route.path;
        var index = path.indexOf(':');
        if (index != -1) {
            return that.$route.path.slice(index+1);
        }
        return false;
    },

    //findModelThatMatchesRoute: function (that, array) {
        //Get the id from the url
        //var path = that.$route.path;
        //var index = path.indexOf(':');
        //if (index != -1) {
        //    return that.$route.path.slice(index+1);
        //}
        //For some reason $route.params was undefined.
        //if (that.$route.params.id) {
        //    var id = that.$route.params.id.slice(1);
        //
        //    //The ids in that[resource] were a string for Chris,
        //    //and an integer for me, so check the datatype here
        //    if (typeof array[0].id == 'number') {
        //        id = parseInt(id, 10);
        //    }
        //
        //    var something = _.findWhere(array, {id: id});
        //    return something;
        //}
        //return {};
    //}
};