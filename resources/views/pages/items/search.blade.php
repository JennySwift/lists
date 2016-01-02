<div id="search-container">

    @include('pages.items.favourites')

    <label>Search all items by title</label>
    <input
        v-on:keyup="filter($event.keyCode)"
        type="text"
        placeholder="title"
        id="filter"/>

    <label>Filter by title</label>
    <input
        v-model="filterTitle"
        type="text"
        placeholder="title"/>

    <label>Filter by priority</label>
    <input
        v-model="filterPriority"
        type="text"
        placeholder="priority"/>

    <div class="form-group">
        <label for="filter-category">Filter by category</label>

        <select v-model="filterCategory" id="filter-category" class="form-control">
            <option v-for="category in categories" v-bind:value="category.id">
                @{{ category.name }}
            </option>
        </select>
    </div>

    <div>
        <button
            v-on:click="filterCategory=''"
            class="btn btn-xs btn-info">
            clear
        </button>
    </div>

</div>