<div id="search-container">

    @include('templates.lists.favourites')

    <label>Search all items by title</label>
    <input
        ng-keyup="filter($event.keyCode)"
        type="text"
        placeholder="title"
        id="filter"/>

    <label>Filter by title</label>
    <input
        ng-model="filterTitle"
        type="text"
        placeholder="title"/>

    <label>Filter by priority</label>
    <input
        ng-model="filterPriority"
        type="text"
        placeholder="priority"/>

    <label>Filter by category</label>
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

</div>