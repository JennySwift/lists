<template>
    <div>
        <router-link
            v-if=redirectTo
            :to="redirectTo"
            v-on:click="hidePopup()"
            tag="button"
            class="btn btn-default"
        >
            Cancel
        </router-link>

        <button
            v-if="showPopup && !redirectTo"
            v-on:click="hidePopup()"
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
    export default {
        methods: {

        },
        props: [
            'save',
            'destroy',
            'redirectTo',
            //For if it's a popup without a redirect path
            'showPopup',
            //Method not a property
            'hidePopup'
        ]
    }
</script>