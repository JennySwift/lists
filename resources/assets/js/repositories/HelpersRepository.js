var HelpersRepository = {

    /**
     *
     * @param data
     * @param status
     */
    handleResponseError: function (data, status, response) {
        $.event.trigger('response-error', [data, status, response]);
        $.event.trigger('hide-loading');
    },

    /**
     *
     */
    closePopup: function ($event, that) {
        if ($event.target.className === 'popup-outer') {
            that.showPopup = false;
        }
    },

    /**
     * 
     * @param boolean
     * @returns {number}
     */
    convertBooleanToInteger: function (boolean) {
        if (boolean) {
            return 1;
        }
        else {
            return 0;
        }
    }
};