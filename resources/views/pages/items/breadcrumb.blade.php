<div id="breadcrumb">
    <div>
        <a v-on:click="goHome()">Home</a>
        <i v-if="breadcrumb.length > 0" class="fa fa-angle-right"></i>
    </div>
    <div v-for="item in breadcrumb">
        <a v-link="{ path: '/items/:' + item.id }">
            @{{ item.title }}
        </a>
        <i v-if="$index !== breadcrumb.length - 1" class="fa fa-angle-right"></i>
    </div>
</div>