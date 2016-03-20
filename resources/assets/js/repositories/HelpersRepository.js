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
};