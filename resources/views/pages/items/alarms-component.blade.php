<script id="alarms-template" type="x-template">

    <div v-if="items.length > 0" id="alarms">
        <h5>Alarms</h5>

        <div id="alarm"></div>

        <item
                v-for="item in items | orderBy 'priority'"
                {{--:items-filter="itemsFilter"--}}
                {{--:title-filter="titleFilter"--}}
                {{--:priority-filter="priorityFilter"--}}
                {{--:category-filter="categoryFilter"--}}
                :show-loading.sync="showLoading"
                :show-item-popup.sync="showItemPopup"
                {{--:show-children="showChildren"--}}
                {{--:items.sync="items"--}}
                :item="item"
                :selected-item.sync="selectedItem"
                {{--:zoomed-item="zoomedItem"--}}
                {{--:get-items="getItems"--}}
                :categories="categories"
                :delete-item="deleteItem"
                {{--:zoom="zoom"--}}
                {{--class="item-with-children"--}}
        >
        </item>
    </div>

</script>