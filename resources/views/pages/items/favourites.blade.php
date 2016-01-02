<div id="favourites">
    <button v-on:click="toggleFavourites()" class="btn btn-info fa fa-star-o"></button>

    <ul v-show="show.favourites" class="list-group">
        <li
                v-for="favourite in favourites"
                v-on:click="goToFavourite(favourite)"
                class="list-group-item">
            @{{ favourite.title }}
        </li>
    </ul>
</div>




