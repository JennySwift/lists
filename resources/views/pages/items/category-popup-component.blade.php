<script id="category-popup-template" type="x-template">

    <div
            v-show="showPopup"
            v-on:click="closePopup($event)"
            class="popup-outer"
    >

        <div id="category-popup" class="popup-inner">

            <div class="form-group">
                <label for="selected-category-name">Name</label>
                <input
                    v-model="selectedCategory.name"
                    type="text"
                    id="selected-category-name"
                    name="selected-category-name"
                    placeholder="name"
                    class="form-control"
                >
            </div>

            <div class="buttons">
                <button v-on:click="showPopup = false" class="btn btn-default">Cancel</button>
                <button v-on:click="deleteCategory()" class="btn btn-danger">Delete</button>
                <button v-on:click="updateCategory()" class="btn btn-success">Save</button>
            </div>

        </div>
    </div>

</script>