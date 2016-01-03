<div id="favourites">
    <button v-on:click="toggleFavourites()" class="btn btn-info fa fa-star-o"></button>

    <ul v-show="showFavourites" class="list-group">
        <li
                v-for="favourite in favouriteItems"
                v-on:click="goToFavourite(favourite)"
                class="list-group-item">
            @{{ favourite.title }}
        </li>
    </ul>
</div>




