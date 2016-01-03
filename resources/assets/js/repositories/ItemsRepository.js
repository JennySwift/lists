var ItemsRepository = {

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
    }
};