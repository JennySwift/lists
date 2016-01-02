<div class="before-item">

    <span class="badge">@{{ item.priority }}</span>

    <button v-on:click="deleteItem(item)" class="btn-danger btn-xs delete-item">
        <span class="fa fa-times"></span>
    </button>

    <i
        v-on:click="zoom(item)"
        class="fa fa-search-plus">
    </i>

    <i
            v-if="item.has_children && !item.children || item.children.length < 1"
            v-on:click="getChildren(item)"
            class="fa fa-plus">
    </i>

    <i
            v-if="item.has_children && item.children.length > 0"
            v-on:click="collapseItem(item)"
            class="fa fa-minus">
    </i>

    <i
            v-if="!item.has_children"
            class="fa fa-plus my-hidden">
    </i>

</div>
