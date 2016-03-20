<script id="categories-template" type="x-template">

    <div id="categories" class="container">

        <feedback></feedback>
        <loading :show-loading="showLoading"></loading>

        <h1>categories</h1>

        <label>Create a new category</label>
        <input
                v-on:keyup.13="insertCategory()"
                type="text"
                placeholder="new category"
                id="new-category"/>

        <button v-on:click="insertCategory()" class="btn btn-success">Create</button>

        <ul>
            <li v-for="category in categories">@{{ category.name }}</li>
        </ul>

    </div>

</script>