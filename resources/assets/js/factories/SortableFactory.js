app.factory('SortableFactory', function ($http) {
    var $object = {};
    var $parent;

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
    $object.findParent = function ($array, $item) {
        if (!$item.parent_id) {
            return false;
        }
        $($array).each(function () {
            if (this.id === $item.parent_id) {
                $parent = this;
                return false;
            }
            if (this.children) {
                $object.findParent(this.children, $item);
            }
        });
        return $parent;
    };

    /**
     * Return an array of the item's siblings including the item itself
     * @param $array
     * @param $item
     * @returns {Array}
     */
    $object.findSiblingsWithItem = function ($array, $item) {
        var $parent = $object.findParent($array, $item);
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
    };

    /**
     * This breaks down when not zoomed on an item
     * and items are expanded several levels. findParent works better.
     * @param $item
     * @param $items
     * @returns {*}
     */
    $object.findParentById = function ($item, $items) {
        if (!$item.parent_id) {
            return false;
        }

        var $parent = _.findWhere(_.flatten($items), {id: $item.parent_id});
        return $parent;
    };

    /**
     * For when item is hovered, setting the index to that of the hovered item
     * @param $index
     */
    $object.setNewIndex = function ($index) {
        $object.newIndex = $index;
    };

    /**
     * For when item is hovered, setting the newParent to that of the hovered item
     * @param $parent
     */
    $object.setNewParent = function ($parent) {
        $object.newParent = $parent;
    };

    /**
     * For when item is hovered, setting the newTarget to that of the hovered item,
     * so I can show the guide at the right place
     * @param $parent
     */
    $object.setNewTarget = function ($target) {
        $object.newTarget = $target;
    };

    $object.setMouseDown = function ($boolean) {
        $object.mouseDown = $boolean;
    };

    return $object;
});
