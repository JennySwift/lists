<template>

    <nav id="navbar" class="navbar navbar-default navbar-fixed-top">
        <div class="container-fluid">
            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="navbar-header">
                <!--<ul class="nav pull-left">-->
                    <!--<li><button id="new-item-btn" class="btn btn-default btn-sm">New Item</button></li>-->

                <!--</ul>-->

                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-1" aria-expanded="false">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
            </div>

            <!-- Collect the nav links, forms, and other content for toggling -->
            <div class="collapse navbar-collapse" id="navbar-1">
                <ul class="nav navbar-nav">
                    <li>
                        <router-link to="/items">Lists</router-link>
                    </li>

                    <li>
                        <a v-on:click="toggleFavouriteItems()">Favourites</a>
                    </li>

                    <li>
                        <a v-on:click="toggleFilter()">Filter</a>
                    </li>
                </ul>

                <ul class="nav navbar-nav navbar-right">
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Menu <span class="caret"></span></a>
                        <ul class="dropdown-menu">
                            <li>
                                <router-link to="/categories">Categories</router-link>
                            </li>

                            <li><a href="/logout">Logout</a></li>
                        </ul>
                    </li>

                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Help <span class="caret"></span></a>
                        <ul class="dropdown-menu">
                            <li>
                                <router-link to="/feedback">Submit Feedback</router-link>
                            </li>

                            <li>
                                <router-link to="/help">Help</router-link>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div><!-- /.navbar-collapse -->
        </div><!-- /.container-fluid -->
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
            toggleFavouriteItems: function () {
                store.toggle('showFavourites');
            }
        },
        mounted: function () {

        }
    }
</script>