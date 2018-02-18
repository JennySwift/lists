<template>

    <nav id="navbar">
        <!--<div>-->
            <!--<button type="button">-->
                <!--<span class="sr-only">Toggle navigation</span>-->
                <!--<span class="icon-bar"></span>-->
                <!--<span class="icon-bar"></span>-->
                <!--<span class="icon-bar"></span>-->
            <!--</button>-->
        <!--</div>-->

        <ul>
            <li class="dropdown">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Menu <span class="caret"></span></a>
                <ul class="dropdown-menu">
                    <li>
                        <router-link to="/categories">Categories</router-link>
                    </li>

                    <li>
                        <router-link to="/trash">Trash</router-link>
                    </li>
                    <li>
                        <router-link to="/help">Help</router-link>
                    </li>
                    <li><a href="/logout">Logout</a></li>
                </ul>
            </li>
        </ul>

        <router-link to="/items">Items</router-link>

        <div v-on:click="toggleFilter()">Filter/Search</div>

    </nav>

</template>

<script>
//    import jQuery from 'jquery'
//    import bootstrap from 'bootstrap'
require('bootstrap');
import store from '../../repositories/Store'

    export default {
        data: function () {
            return {

            };
        },
        computed: {
            path: function () {
              return this.$route.path;
            }
        },
        components: {},
        methods: {

            /**
             *
             */
            toggleFilter: function () {
               store.toggle('showFilter');
            },

            /**
             *
             */
//            toggleFavouriteItems: function () {
//                store.toggle('showFavourites');
//            }
        },
        mounted: function () {

        }
    }
</script>