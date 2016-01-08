<ul v-if="item.children.length > 0">

    <item
            v-if="!breadcrumb || breadcrumb.length < 1"
            v-for="item in item.children | itemsFilter"
            :items-filter="itemsFilter"
            :title-filter="titleFilter"
            :priority-filter="priorityFilter"
            :category-filter="categoryFilter"
            :show-loading.sync="showLoading"
            :show-item-popup.sync="showItemPopup"
            :show-children="showChildren"
            :items.sync="items"
            :item="item"
            :selected-item.sync="selectedItem"
            :zoomed-item="zoomedItem"
            :get-items="getItems"
            :categories="categories"
            :zoom="zoom"
            :delete-item="deleteItem"
            class="item-with-children"
    >
    </item>
</ul>