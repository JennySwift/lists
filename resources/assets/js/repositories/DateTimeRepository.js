var DateTimeRepository = {

    /**
     *
     * @param dateAndTime
     * @returns {*}
     */
    convertToDateTime: function (dateAndTime) {
        return Date.create(dateAndTime).format('{yyyy}-{MM}-{dd} {HH}:{mm}:{ss}');
        //if (Date.parse(dateAndTime)) {
        //    return Date.parse(dateAndTime).toString('yyyy-MM-dd HH:mm:ss');
        //}
        //
        //return null;
    },

    /**
     *
     * @param dateTime
     * @param format - format to convert to
     * @returns {*}
     */
    convertFromDateTime: function (dateTime, format) {
        format = format || 'DD/MM/YY hh:mma';
        return moment(dateTime, 'YYYY-MM-DD HH:mm:ss').format(format);
    }
};