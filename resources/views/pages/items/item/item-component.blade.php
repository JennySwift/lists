<script id="item-template" type="x-template">

<li>
    
    <div class="item">

        @include('pages.items.item.before-item')

        <div
                v-if="!item.html"
                v-on:click="showItemPopup(item)"
                class="item-content"
        >

            <div class="title">@{{ item.title }}</div>

            <div class="big-screen">
                <i v-if="item.body" class="fa fa-sticky-note note"></i>
                <i v-if="item.pinned" class="fa fa-map-pin pinned"></i>
                <i v-if="item.alarm" class="fa fa-bell alarm"></i>
                <i v-if="item.timeLeft" class="">@{{ item.timeLeft | timeLeftFilter }}</i>
                <span v-if="item.notBefore" class="not-before">Not before @{{ item.notBefore | dateTimeFilter}}</span>

                <div v-if="item.recurringUnit" class="recurring">
                    <i class="fa fa-refresh"></i>
                    <span>Repeats every @{{ item.recurringFrequency }} @{{ item.recurringUnit }}</span>
                    <span v-if="item.recurringFrequency > 1">s</span>
                </div>
            </div>

        </div>

        @include('pages.items.item.after-item')

    </div>

    @include('pages.items.item.children')
</li>

</script>
