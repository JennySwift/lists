<script id="favourite-items-template" type="x-template">

    <ul v-show="showFavourites" id="favourite-items" class="list-group">
        <li
                v-for="item in favouriteItems"
                v-link="{ path: '/items/:' + item.id }"
                v-on:click="showFavourites = false"
                class="list-group-item">
            @{{ item.title }}
            <span class="badge">@{{ item.id }}</span>
        </li>
    </ul>

</script>