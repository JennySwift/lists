<div class="before-item">
    
    <span class="badge priority">@{{ item.priority }}</span>

    <span v-if="item.urgency"
        v-bind:class="{'urgency-one': item.urgency == 1}"
        class="badge"
    >
        @{{ item.urgency }}
    </span>

    <span v-if="!item.urgency"
          class="badge my-hidden"
    >
        0
    </span>

    <button
        v-on:click="deleteItem(item)"
        class="btn-danger btn-xs delete-item big-screen"
    >
        <span class="fa fa-times"></span>
    </button>

    <i
        v-link="{ path: '/items/:' + item.id }"
        class="fa fa-search-plus big-screen"
    >
    </i>

    <i
            v-if="item.has_children && (!item.children || item.children.length === 0)"
            v-on:click="getItems('expand', item)"
            class="fa fa-plus big-screen"
    >
    </i>

    <i
            v-if="item.has_children && item.children && item.children.length > 0"
            v-on:click="collapseItem(item)"
            class="fa fa-minus big-screen"
    >
    </i>

    <i
            v-if="!item.has_children"
            class="fa fa-plus my-hidden big-screen"
    >
    </i>



    {{--Actions dropdown for small screens--}}
    <div class="btn-group small-screen">

        <button
            type="button"
            class="btn btn-default btn-xs dropdown-toggle"
            data-toggle="dropdown"
            aria-haspopup="true"
            aria-expanded="false"
        >
            <span class="caret"></span>
        </button>

        <ul class="dropdown-menu">
            <li>
                <a
                    href="#"
                    v-on:click="deleteItem(item)"
                    class="fa fa-times"
                >
                </a>
            </li>

            <li>
                <a
                    href="#"
                    v-link="{ path: '/items/:' + item.id }"
                    class="fa fa-search-plus"
                >
                </a>
            </li>
        </ul>

    </div>

    <i
            v-if="item.has_children && (!item.children || item.children.length === 0)"
            v-on:click="getItems('expand', item)"
            class="fa fa-plus small-screen"
    >
    </i>

    <i
            v-if="item.has_children && item.children && item.children.length > 0"
            v-on:click="collapseItem(item)"
            class="fa fa-minus small-screen"
    >
    </i>

    <i
            v-if="!item.has_children"
            class="fa fa-plus my-hidden small-screen"
    >
    </i>



    <i v-if="item.body" class="fa fa-sticky-note note small-screen"></i>
    <i v-if="item.pinned" class="fa fa-map-pin pinned small-screen"></i>
    <i v-if="item.alarm" class="fa fa-bell alarm small-screen"></i>
    <i v-if="item.timeLeft" class="small-screen">@{{ item.timeLeft | timeLeftFilter }}</i>

    <span
            v-if="item.category"
            class="label label-primary small-screen"
    >
        @{{ item.category.name }}
    </span>

</div>
