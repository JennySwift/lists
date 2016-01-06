<script id="alarms-template" type="x-template">

    <div id="alarms">
        <h5>Alarms</h5>

        <div id="alarm"></div>

        <item
                v-for="item in alarms | orderBy 'priority'"
                :show-loading.sync="showLoading"
                :show-item-popup.sync="showItemPopup"
                :show-children="showChildren"
                :items.sync="items"
                :item="item"
                :selected-item="selectedItem"
                :zoomed-item="zoomedItem"
                :get-items="getItems"
                :categories="categories"
                :zoom="zoom"
                class="item-with-children"
        >
        </item>
    </div>

</script>