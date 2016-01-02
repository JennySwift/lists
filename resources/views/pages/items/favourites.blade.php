<div id="favourites">
    <button ng-click="toggleFavourites()" class="btn btn-info fa fa-star-o"></button>

    <ul ng-show="show.favourites" class="list-group">
        <li
                ng-repeat="favourite in favourites"
                ng-click="goToFavourite(favourite)"
                class="list-group-item">
            [[favourite.title]]
        </li>
    </ul>
</div>




