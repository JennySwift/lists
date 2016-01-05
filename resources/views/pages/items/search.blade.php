<div id="search-container">

    @include('pages.items.favourite-items')

    <label>Search all items by title</label>
    <input
        v-on:keyup.13="filter"
        type="text"
        placeholder="title"
        id="filter"/>

    <label>Filter by title</label>
    <input
        v-model="titleFilter"
        type="text"
        placeholder="title"/>

    <label>Filter by priority</label>
    <input
        v-model="priorityFilter"
        type="text"
        placeholder="priority"/>

    <label>Filter in by urgency</label>
    <input
            v-model="urgencyFilter"
            type="text"
            placeholder="urgency"/>

    <label>Filter out by urgency >=</label>
    <input
            v-model="urgencyOutFilter"
            type="text"
            placeholder="urgency"/>

    <div class="form-group">
        <label for="category-filter">Filter by category</label>

        <select v-model="categoryFilter" id="category-filter" class="form-control">
            <option v-for="category in categories" v-bind:value="category.id">
                @{{ category.name }}
            </option>
        </select>
    </div>

    <div>
        <button
            v-on:click="categoryFilter=''"
            class="btn btn-xs btn-info">
            clear
        </button>
    </div>

</div>