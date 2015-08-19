<div id="search-container">
    <input ng-keyup="filter($event.keyCode)" type="text" placeholder="search" id="filter"/>
    <input ng-model="filterPriority" type="text" placeholder="filter by priority"/>

    <select ng-model="filterCategory" class="form-control">
        <option
            ng-repeat="category in categories"
            ng-value="category.id">
            [[category.name]]
        </option>
    </select>

    <div>
        <button ng-click="filterCategory=''" class="btn btn-xs btn-info">clear</button>
    </div>


</div>