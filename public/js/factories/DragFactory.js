app.factory('DragFactory', function ($http) {
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

    $object.setNewIndex = function ($index) {
        $object.newIndex = $index;
    };

    return $object;
});
