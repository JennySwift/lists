<div id="favourites">
    <button v-on:click="toggleFavourites()" class="btn btn-info fa fa-star-o"></button>

    <ul v-show="showFavourites" class="list-group">
        <li
                v-for="item in favouriteItems"
                v-link="{ path: '/items/:' + item.id }"
                v-on:click="showFavourites = false"
                class="list-group-item">
            @{{ item.title }}
            <span class="badge">@{{ item.id }}</span>
        </li>
    </ul>
</div>




