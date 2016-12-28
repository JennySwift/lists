require('sugar');

module.exports = {
    ready: function () {
        //Set Sugar to use Australian date formatting
        Date.setLocale('en-AU');
        store.getCategories();
        store.getItems('zoom');
    }
};