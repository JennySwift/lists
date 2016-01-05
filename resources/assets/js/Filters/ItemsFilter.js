//Vue.filter('itemsFilter', function (items) {
//    var that = this;
//
//    //Sort
//    items = _.chain(items).sortBy('id').sortBy('priority').value();
//
//    //Filter
//    return items.filter(function (item) {
//        var filteredIn = item.title.toLowerCase().indexOf(that.titleFilter.toLowerCase()) !== -1;
//
//        if (that.priorityFilter && item.priority != that.priorityFilter) {
//            filteredIn = false;
//        }
//        else if (that.categoryFilter && item.category_id !== that.categoryFilter) {
//            filteredIn = false;
//        }
//
//        return filteredIn;
//    });
//});