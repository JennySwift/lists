<template>

    <nav class="navbar navbar-default navbar-fixed-top">
        <div class="container-fluid">
            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="navbar-header">
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
                    <li v-bind:class="{'active': path === '/items' }">
                        <router-link to="/items">Lists</router-link>
                    </li>

                    <li>
                        <a v-on:click="toggleFavouriteItems()" class="fa fa-star-o"></a>
                    </li>

                    <li>
                        <a v-on:click="toggleFilter()"><i class="fa fa-search"></i></a>
                    </li>
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Menu <span class="caret"></span></a>
                        <ul class="dropdown-menu">
                            <li>
                                <router-link to="/categories">Categories</router-link>
                            </li>

                            <li><a href="/auth/logout">Logout</a></li>
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
    require('bootstrap');

    module.exports = {
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
                $.event.trigger('toggle-filter');
            },

            /**
             *
             */
            toggleFavouriteItems: function () {
                store.toggle('showFavourites');
            }
        },
        props: [
            'showFilter'
        ],
        ready: function () {

        }
    };
</script>