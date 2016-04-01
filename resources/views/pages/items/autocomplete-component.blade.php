<script id="autocomplete-template" type="x-template">

    <div>
        <div class="form-group">
            <label :for="autocompleteFieldId">@{{ autocompleteField | capitalize }}</label>
            <input
                    v-model="chosenOption.title"
                    v-on:keyup="respondToKeyup($event.keyCode)"
                    {{--v-on:blur="showDropdown = false"--}}
                    type="text"
                    :id="autocompleteFieldId"
                    :name="autocompleteFieldId"
                    :placeholder="autocompleteField"
                    class="form-control"
            >
        </div>

        <div
                v-show="showDropdown"
                class="autocomplete-dropdown"
        >
            <div
                    v-for="option in autocompleteOptions"
                    v-bind:class="{'selected': currentIndex === $index}"
                    v-on:mouseover="hoverItem($index)"
                    v-on:mousedown="selectOption($index)"
                    class="autocomplete-option"
            >
                <div >@{{ option.title }}</div>
                <span class="label label-primary">@{{ option.category.name }}</span>
            </div>
        </div>
    </div>

</script>