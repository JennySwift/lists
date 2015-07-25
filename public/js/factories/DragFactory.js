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

    return $object;
});
