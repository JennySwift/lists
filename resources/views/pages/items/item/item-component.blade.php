<script id="item-template" type="x-template">

<li>
    <div class="item">

        @include('pages.items.item.before-item')
        <feedback></feedback>

        <div
                v-if="!item.html"
                v-on:click="openItemPopup(item)"
                class="item-content">

            <div class="title">@{{ item.title }}</div>

            <div class="big-screen">
                <i v-if="item.body" class="fa fa-sticky-note note"></i>
                <i v-if="item.pinned" class="fa fa-map-pin pinned"></i>
                <i v-if="item.alarm" class="fa fa-bell alarm"></i>
                <i v-if="item.timeLeft" class="">@{{ item.timeLeft | timeLeftFilter }}</i>
                <span v-if="item.notBefore" class="not-before">Not before @{{ item.notBefore | dateTimeFilter}}</span>
            </div>

        </div>

        {{--<div--}}
                {{--v-if="item.html"--}}
                {{--v-on:click="openItemPopup(item)"--}}
                {{--ng-bind-html="item.html"--}}
                {{--class="item-content">--}}

            {{--<div class="note">--}}
                {{--<i v-if="item.body" class="fa fa-sticky-note"></i>--}}
            {{--</div>--}}

            {{--<div class="pinned">--}}
                {{--<i v-if="item.pinned" class="fa fa-map-pin"></i>--}}
            {{--</div>--}}
        {{--</div>--}}

        @include('pages.items.item.after-item')

    </div>

    @include('pages.items.item.children')
</li>

</script>
