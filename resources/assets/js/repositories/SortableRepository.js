var SortableRepository = {
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
     * @param $array
     * @param $item
     * @returns {*}
     */
     findParent: function ($array, $item) {
        if (!$item.parent_id) {
            return false;
        }
        $($array).each(function () {
            if (this.id === $item.parent_id) {
                $parent = this;
                return false;
            }
            if (this.children) {
                findParent(this.children, $item);
            }
        });

        return $parent;
    },

    /**
     * Return an array of the item's siblings including the item itself
     * @param $array
     * @param $item
     * @returns {Array}
     */
    findSiblingsWithItem: function ($array, $item) {
        var $parent = findParent($array, $item);
        var $siblings = [];

        if ($parent) {
            $siblings = $parent.children;
        }
        else {
            $($array).each(function () {
                $siblings.push(this);
            });
        }

        return $siblings;
    },

    /**
     * This breaks down when not zoomed on an item
     * and items are expanded several levels. findParent works better.
     * @param $item
     * @param $items
     * @returns {*}
     */
    findParentById: function ($item, $items) {
        if (!$item.parent_id) {
            return false;
        }

        var $parent = _.findWhere(_.flatten($items), {id: $item.parent_id});
        return $parent;
    },

    /**
     * For when item is hovered, setting the index to that of the hovered item
     * @param $index
     */
    setNewIndex: function ($index) {
        newIndex = $index;
    },

    /**
     * For when item is hovered, setting the newParent to that of the hovered item
     * @param $parent
     */
    setNewParent: function ($parent) {
        newParent = $parent;
    },

    /**
     * For when item is hovered, setting the newTarget to that of the hovered item,
     * so I can show the guide at the right place
     * @param $parent
     */
    setNewTarget: function ($target) {
        newTarget = $target;
    },

    setMouseDown: function ($boolean) {
        mouseDown = $boolean;
    },

};
