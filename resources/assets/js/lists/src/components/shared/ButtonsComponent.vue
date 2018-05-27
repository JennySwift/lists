<template>
    <div class="buttons-container">

        <f7-button
            v-if="redirectTo"
            :href="redirectTo"
            text="Cancel"
            color="grey"
        >
        </f7-button>

        <!--<router-link-->
        <!--v-if="redirectTo"-->
        <!--:to="redirectTo"-->
        <!--tag="button"-->
        <!--class="btn btn-default"-->
        <!--&gt;-->
        <!--Cancel-->
        <!--</router-link>-->

        <f7-button
            v-if="destroy"
            v-on:click="destroy()"
            text="Delete"
            color="red"
        >
        </f7-button>

        <!--Not adding the redirectTo here because that's done with the JS in case there is an error and we don't want to redirect-->
        <f7-button
            v-if="save"
            v-on:click="save()"
            text="Save"
            color="green"
        >
        </f7-button>
    </div>
</template>

<script>
    export default {
        props: [
            'save',
            'destroy',
            'redirectTo'
        ]
    }
</script>

<style lang="scss" type="text/scss">
    .buttons-container {
        display: flex;
        width: 100%;
        > * {
            flex-grow: 1;
            margin: 0 5px;
            &:first-child {
                margin-left: 0px;
            }
            &:last-child {
                margin-right: 0px;
            }
        }
    }
</style>