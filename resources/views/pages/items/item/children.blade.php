<ul v-if="item.children">

    <item
        v-if="!breadcrumb || breadcrumb.length < 1"
        v-for="item in items | filterBy itemsFilter"
        :show-loading="showLoading"
        :show-item-popup="showItemPopup"
        :items="items"
        :item="item"
        :item-popup="itemPopup"
        :zoomed-item="zoomedItem"
        :categories="categories"
        class="item-with-children"
    >
    </item>
</ul>