app.factory('SortableFactory', function ($http) {
    var $object = {};
    var $parent;

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
        //$siblings = _.without($siblings, $item);

        return $siblings;
    };

    /**
     * $short_path is an array of indexes to the item, for example:
     * [0,2,1]
     */
    $object.findParentByPath = function ($item, $short_path) {
        for (var i = 0; i < $short_path.length; i++) {
            if (i > 0 && i < $short_path.length - 1) {
                $item = $item.children[$short_path[i]];
            }
        }

        return $item;
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
