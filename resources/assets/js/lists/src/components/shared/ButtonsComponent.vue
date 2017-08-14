<template>
    <div class="btns">
        <router-link
            v-if=redirectTo
            v-on:click.native="hidePopup"
            :to="redirectTo"
            tag="button"
            class="btn btn-default"
        >
            Cancels
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