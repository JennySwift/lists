<script id="urgent-items-template" type="x-template">

    <div id="urgent-items">
        
        <h5>Urgent</h5>

        <item
                v-for="item in items"
                :show-loading.sync="showLoading"
                :show-item-popup.sync="showItemPopup"
                :item="item"
                :categories="categories"
                :delete-item="deleteItem"
                :selected-item.sync="selectedItem"
                class="item-with-children"
        >
        </item>
    </div>

</script>