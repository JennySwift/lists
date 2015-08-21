<div id="search-container">

    <input
        ng-keyup="filter($event.keyCode)"
        type="text"
        placeholder="search all items by title"
        id="filter"/>

    <input
        ng-model="filterTitle"
        type="text"
        placeholder="filter by title"/>

    <input
        ng-model="filterPriority"
        type="text"
        placeholder="filter by priority"/>

    <select ng-model="filterCategory" class="form-control">
        <option
            ng-repeat="category in categories"
            ng-value="category.id">
            [[category.name]]
        </option>
    </select>

    <div>
        <button
            ng-click="filterCategory=''"
            class="btn btn-xs btn-info">
            clear
        </button>
    </div>

    <div>
        <button ng-click="toggleFavourites()" class="btn btn-info fa fa-star-o"></button>
    </div>

    <div ng-show="show.favourites" id="favourites">
        <ul class="list-group">
            <li
                ng-repeat="favourite in favourites"
                ng-click="goToFavourite(favourite)"
                class="list-group-item">
                [[favourite.title]]
            </li>
        </ul>
    </div>

</div>