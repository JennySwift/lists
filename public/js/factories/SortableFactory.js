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
     * This doesn't work anymore since I started ordering by priority.
     * $short_path is an array of indexes to the item, for example:
     * [0,2,1]
     */
    //$object.findParentByPath = function ($item, $short_path) {
    //    for (var i = 0; i < $short_path.length; i++) {
    //        if (i > 0 && i < $short_path.length - 1) {
    //            $item = $item.children[$short_path[i]];
    //        }
    //    }
    //
    //    return $item;
    //};

    /**
     * For when items are ordered by priority, not index.
     * $parent variable is in the process of being found, so it isn't the
     * correct value until the end.
     * $path is an array of indexes to the item, for example:
     * [0,2,1]
     * //Todo: It might make more sense to make the $item.path_to_item an array of ids rather than indexes since I'm now ordering by priority
     */
    //$object.findParentByPath = function ($item, $items) {
    //    if (!$item.parent_id) {
    //        return false;
    //    }
    //
    //    var $path = $item.path_to_item;
    //
    //    for (var i = 0; i < $path.length; i++) {
    //        if (i === 0) {
    //            $parent = _.findWhere($items, {index: i});
    //        }
    //        //Check i is less than $short_path.length -1, otherwise it would
    //        //be the child and not the parent
    //        else if (i < $path.length - 1) {
    //            $parent = _.findWhere($parent.children, {index: $path[i]});
    //        }
    //    }
    //
    //    return $parent;
    //};

    $object.findParentById = function ($item, $items) {
        if (!$item.parent_id) {
            return false;
        }

        return _.findWhere(_.flatten($items), {id: $item.parent_id});
    };

    /**
     * For when I have the item object (in the popup) but need to modify it
     * in the original $scope.items array
     * @param $item
     */
    //$object.findItemByPath = function ($item) {
    //    var $path = $item.path_to_item;
    //
    //};

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
