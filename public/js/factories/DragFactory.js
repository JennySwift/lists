app.factory('DragFactory', function ($http) {
    var $object = {};

    $object.findParent = function ($array, $item) {
        var $parent;
        if (!$item.parent_id) {
            return false;
        }
        $($array).each(function () {
            if (this.id === $item.parent_id) {
                return $parent = this;
            }
            if (this.children) {
                $object.findParent(this.children, $item);
            }
        });
        return $parent;
    };

    $object.findSiblings = function ($array, $item) {
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

    $object.setNewIndex = function ($index) {
        $object.newIndex = $index;
    };

    return $object;
});
