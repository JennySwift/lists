<template>
    <ul id="navbar" style="z-index:1000">

        <li id="menu-dropdown" class="dropdown">
            <a href="#" class="dropdown-toggle fa fa-bars" data-toggle="dropdown"><span class="caret"></span></a>
            <ul class="dropdown-menu" role="menu">
                <li>
                    <a v-link="{ path: '/categories/'  }">categories</a>
                </li>

                <!--<li>-->
                    <!--<a v-link="{ path: '/trash/'  }"><i class="fa fa-trash"></i></a>-->
                <!--</li>-->

                <!--<li>-->
                    <!--<a-->
                        <!--v-on:click="undoDeleteItem()"-->
                        <!--href="#">-->
                        <!--undo delete item-->
                    <!--</a>-->
                <!--</li>-->

                <li><a href="/auth/logout">Logout</a></li>
            </ul>
        </li>

        <li>
            <a v-link="{ path: '/items/' }">lists</a>
        </li>

        <li>
            <a v-on:click="toggleFavouriteItems()" class="fa fa-star-o"></a>
        </li>

        <li>
            <a v-on:click="toggleFilter()"><i class="fa fa-search"></i></a>
        </li>

        <li id="menu-dropdown" class="dropdown">
            <a href="#" data-toggle="dropdown">
                Help
                <span class="caret"></span>
            </a>
            <ul class="dropdown-menu" role="menu">
                <!--<li>-->
                    <!--<a v-link="{path: '/feedback'}">Submit feedback</a>-->
                <!--</li>-->

                <li>
                    <a v-link="{path: '/help'}">Help</a>
                </li>
            </ul>
        </li>

    </ul>

    <div id="playground-notice">This is for trial purposes only. The data is reset regularly. Other people using this trial version may see the data you enter, and vice versa.</div>

</template>

<script>
    require('bootstrap');

    module.exports = {
        template: '#navbar-template',
        data: function () {
            return {

            };
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