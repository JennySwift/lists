<template>
    <div id="help-page">

        <h3>Adding A New Item</h3>
        <p>To add a new item, click in the title field at the top of the page. Fill in the required fields, and either hit enter on your keyboard, or click the 'Save' button.</p>

        <h3>Editing an item</h3>
        <p>To edit an item, click on it, make your changes, then click the 'Save' button.</p>

        <h3>Navigation Menu</h3>
        <p>The buttons to show/hide the favourite item navigation and the filter will only work from the main page where the items are.</p>

        <h3>Favourite Items</h3>
        <p>Items can be made favourites so they are quicker to navigate to. To make an item a favourite, click on the item to edit it, then click the star button. Then save the item. You can view and navigate to your favourite items by clicking the star in the navigation menu at the top of the page. Click this same star again to hide the navigation for the favourite items.</p>

        <h3>Filtering Items</h3>
        <p>Items can be filtered. This makes it easier to focus on the items that are more important to you at the time. To toggle the filter visibility, click the search icon in the navigation menu.</p>

        <h3>Navigation</h3>
        <p>To navigate to items, hover over the item and click the magnifying glass icon that appears. To see an item's children (if it has any), click the plus icon next to the item. The breadcrumb can also be used for navigation.</p>

        <h3>Adding Children</h3>
        <p>To add children to an item, navigate to that item. (See above.) Any new items you create from there will be added as children of that item.</p>

        <h3>Deleting recurring items</h3>
        <p>To delete all instances of a recurring item, you first need to change the item's recurring unit to 'none.'</p>
        <p>To do so, click on the item to open the popup and change the 'recurring item' value from the dropdown. Then click the 'save' button.</p>
        <p>Now the item can be deleted.</p>
    </div>
</template>

<script>
    module.exports = {
        template: '#help-page-template',
        data: function () {
            return {

            };
        },
        components: {},
        methods: {

        },
        props: [
            //data to be received from parent
        ],
        ready: function () {

        }
    };
</script>
