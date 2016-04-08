<ul v-if="item.children.length > 0">

    <item
            v-if="!breadcrumb || breadcrumb.length < 1"
            v-for="item in item.children | itemsFilter"
            :filters="filters"
            :show-children="showChildren"
            :items.sync="items"
            :item="item"
            :selected-item.sync="selectedItem"
            :zoomed-item="zoomedItem"
            :get-items="getItems"
            :categories="categories"
            :zoom="zoom"
            class="item-with-children"
    >
    </item>
</ul>