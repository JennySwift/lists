<template>
    <div>
        <router-link
            v-if=redirectTo
            :to="redirectTo"
            tag="button"
            class="btn btn-default"
        >
            Cancel
        </router-link>

        <button
            v-if="showPopup"
            v-on:click="showPopup = false"
            class="btn btn-default"
        >
            Cancel
        </button>

        <button
            v-if="destroy"
            v-on:click="destroy()"
            class="btn btn-danger"
        >
            Delete
        </button>

        <button
            v-if="save"
            v-on:click="save()"
            class="btn btn-success"
        >
            Save
        </button>
    </div>
</template>

<script>
    module.exports = {
        props: [
            'save',
            'destroy',
            'redirectTo',
            //For if it's a popup without a redirect path
            'showPopup'
        ]
    };
</script>