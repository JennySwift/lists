<template>
    <div id="help-page">

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
