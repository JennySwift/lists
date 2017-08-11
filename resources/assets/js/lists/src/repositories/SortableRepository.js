/**
 * This file currently isn't being used.
 * @type {{findSiblingsWithItem: SortableRepository.findSiblingsWithItem, findParentById: SortableRepository.findParentById, setNewIndex: SortableRepository.setNewIndex, setNewParent: SortableRepository.setNewParent, setNewTarget: SortableRepository.setNewTarget, setMouseDown: SortableRepository.setMouseDown}}
 */
export default {

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

}
