
<script id="trash-template" type="x-template">

    <loading :show-loading="showLoading"></loading>

    <div id="lists" class="container">
        <ul id="items">

            <item
                    v-for="item in items"
                    :show-loading.sync="showLoading"
                    {{--:show-item-popup="showItemPopup"--}}
                    {{--:show-children="showChildren"--}}
                    :items.sync="items"
                    :item="item"
                    :item-popup="false"
                    {{--:zoomed-item="zoomedItem"--}}
                    {{--:get-items="getItems"--}}
                    {{--:categories="categories"--}}
                    {{--:zoom="zoom"--}}
                    class="item-with-children"
            >
            </item>

            <div v-if="items.length === 0">No items here</div>
        </ul>
    </div>

</script>