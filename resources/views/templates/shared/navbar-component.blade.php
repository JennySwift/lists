<script id="navbar-template" type="x-template">

    <ul id="navbar" style="z-index:1000">

        @if (Auth::guest())
            <li>
                <a href="/auth/login">Login</a>
            </li>
            <li>
                <a href="/auth/register">Register</a>
            </li>

        @else

            <li id="menu-dropdown" class="dropdown">
                <a href="#" class="dropdown-toggle fa fa-bars" data-toggle="dropdown"><span class="caret"></span></a>
                <ul class="dropdown-menu" role="menu">
                    <li><a href="/auth/logout">Logout <?php echo Auth::user()->name; ?></a></li>
                </ul>
            </li>

            <li id="menu-dropdown" class="dropdown">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                    actions
                    <span class="caret"></span>
                </a>
                <ul class="dropdown-menu" role="menu">
                    <li>
                        <a
                                v-on:click="undoDeleteItem()"
                                href="#">
                            undo delete item
                        </a>
                    </li>
                </ul>
            </li>

            <li>
                <a v-link="{ path: '/items/' }">lists</a>
            </li>

            <li>
                <a v-link="{ path: '/categories/'  }">categories</a>
            </li>

            <li>
                <a v-link="{ path: '/trash/'  }"><i class="fa fa-trash"></i></a>
            </li>

            <li>
                <a v-on:click="toggleFilter()"><i class="fa fa-search"></i></a>
            </li>

        @endif

    </ul>


</script>
