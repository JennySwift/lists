<script id="filter-template" type="x-template">

    <div v-show="showFilter" id="search-container">

        @include('pages.items.favourite-items')

        <label>Search all items by title</label>
        <input
                v-on:keyup.13="filter"
                type="text"
                placeholder="title"
                id="filter"/>

        <label>Filter by title</label>
        <input
                v-model="filters.title"
                type="text"
                placeholder="title"/>

        <label>Filter by priority</label>
        <input
                v-model="filters.priority"
                type="text"
                placeholder="priority"/>

        <label>Filter in by urgency</label>
        <input
                v-model="filters.urgency"
                type="text"
                placeholder="urgency"/>

        <label>Filter out by urgency >=</label>
        <input
                v-model="filters.urgencyOut"
                type="text"
                placeholder="urgency"/>

        <div class="checkbox-container">
            <label for="filter-not-before">Do not show items with a not-before time after the current time</label>
            <input
                v-model="filters.notBefore"
                id="filter-not-before"
                type="checkbox"
            >
        </div>

        <div class="form-group">
            <label for="category-filter">Filter by category</label>

            <select v-model="filters.category" id="category-filter" class="form-control">
                <option v-for="category in categories" v-bind:value="category.id">
                    @{{ category.name }}
                </option>
            </select>
        </div>

        <div>
            <button
                    v-on:click="filters.category=''"
                    class="btn btn-xs btn-info">
                clear
            </button>
        </div>

    </div>

</script>