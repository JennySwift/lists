<div id="urgent-items">
    <h5>Urgent</h5>

    <item
            v-for="item in urgentItems | itemsFilter"
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
</div>