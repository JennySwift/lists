<!--<template>-->
    <!--<popup-->
        <!--:show-popup.sync="showPopup"-->
        <!--id="category-popup"-->
        <!--:redirect-to="redirectTo"-->
        <!--:update="updateCategory"-->
        <!--:destroy="deleteCategory"-->
    <!--&gt;-->
        <!--&lt;!&ndash;<div slot="content">&ndash;&gt;-->
            <!--&lt;!&ndash;<div class="input-group-container">&ndash;&gt;-->
                <!--&lt;!&ndash;<input-group&ndash;&gt;-->
                    <!--&lt;!&ndash;label="Name:"&ndash;&gt;-->
                    <!--&lt;!&ndash;:model.sync="selectedCategory.name"&ndash;&gt;-->
                    <!--&lt;!&ndash;:enter="updateCategory"&ndash;&gt;-->
                    <!--&lt;!&ndash;id="selected-category-name"&ndash;&gt;-->
                <!--&lt;!&ndash;&gt;&ndash;&gt;-->
                <!--&lt;!&ndash;</input-group>&ndash;&gt;-->
            <!--&lt;!&ndash;</div>&ndash;&gt;-->

            <!--&lt;!&ndash;<buttons&ndash;&gt;-->
                <!--&lt;!&ndash;:save="updateCategory"&ndash;&gt;-->
                <!--&lt;!&ndash;:destroy="deleteCategory"&ndash;&gt;-->
                <!--&lt;!&ndash;:redirect-to="redirectTo"&ndash;&gt;-->
            <!--&lt;!&ndash;&gt;&ndash;&gt;-->
            <!--&lt;!&ndash;</buttons>&ndash;&gt;-->

        <!--&lt;!&ndash;</div>&ndash;&gt;-->
    <!--</popup>-->

<!--</template>-->

<!--<script>-->
    <!--import store from '../repositories/Store'-->
    <!--import helpers from '../repositories/Helpers'-->
    <!--export default {-->
        <!--template: '#category-popup-template',-->
        <!--data: function () {-->
            <!--return {-->
                <!--selectedCategory: {},-->
                <!--showPopup: false,-->
                <!--shared: store.state,-->
                <!--redirectTo: '/categories'-->
            <!--};-->
        <!--},-->
        <!--components: {},-->
        <!--methods: {-->

<!--//            /**-->
<!--//            *-->
<!--//            */-->
<!--//            updateCategory: function () {-->
<!--//                var data = {-->
<!--//                    name: this.selectedCategory.name-->
<!--//                };-->
<!--//-->
<!--//                helpers.put({-->
<!--//                    url: '/api/categories/' + this.selectedCategory.id,-->
<!--//                    data: data,-->
<!--//                    property: 'categories',-->
<!--//                    message: 'Category updated',-->
<!--//                    redirectTo: this.redirectTo,-->
<!--//                    callback: function (response) {-->
<!--//-->
<!--//                    }.bind(this)-->
<!--//                });-->
<!--//            },-->
<!--//-->
<!--//            /**-->
<!--//            *-->
<!--//            */-->
<!--//            deleteCategory: function () {-->
<!--//                helpers.delete({-->
<!--//                    url: '/api/categories/' + this.selectedCategory.id,-->
<!--//                    array: 'categories',-->
<!--//                    itemToDelete: this.selectedCategory,-->
<!--//                    message: 'Category deleted',-->
<!--//                    redirectTo: this.redirectTo,-->
<!--//                    callback: function () {-->
<!--//                        this.showPopup = false;-->
<!--//                    }.bind(this)-->
<!--//                });-->
<!--//            },-->

            <!--/**-->
             <!--*-->
             <!--*/-->
            <!--closePopup: function ($event) {-->
                <!--helpers.closePopup($event, this);-->
            <!--},-->

            <!--/**-->
             <!--*-->
             <!--*/-->
            <!--listen: function () {-->
                <!--var that = this;-->
                <!--$(document).on('show-category-popup', function (event, category) {-->
                    <!--that.selectedCategory = category;-->
                    <!--that.showPopup = true;-->
                <!--});-->
            <!--}-->
        <!--},-->
        <!--mounted: function () {-->
            <!--this.listen();-->
        <!--}-->
    <!--}-->
<!--</script>-->
