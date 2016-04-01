var Autocomplete = Vue.component('autocomplete', {
    template: '#autocomplete-template',
    data: function () {
        return {
            autocompleteOptions: [],
            chosenOption: {
                title: ''
            },
            showDropdown: false,
            currentIndex: 0,
            timeSinceKeyPress: 0,
            interval: '',
            startedCounting: false
        };
    },
    components: {},
    methods: {

        /**
         *
         * @param keycode
         */
        respondToKeyup: function (keycode) {
            if (keycode !== 13 && keycode !== 38 && keycode !== 40 && keycode !== 39 && keycode !== 37) {
                //not enter, up, down, right or left arrows
                this.startCounting();
            }
            else if (keycode === 38) {
                //up arrow pressed
                if (this.currentIndex !== 0) {
                    this.currentIndex--;
                }
            }
            else if (keycode === 40) {
                //down arrow pressed
                if (this.autocompleteOptions.length - 1 !== this.currentIndex) {
                    this.currentIndex++;
                }
            }
            else if (keycode === 13) {
                this.respondToEnter();
            }
        },

        /**
         * Called each time a key is pressed that would fire the request to get the results (not enter, arrows, etc)
         * So a request isn't fired each time a key is pressed if the user types quickly
         */
        startCounting: function () {
            var that = this;
            clearInterval(this.interval);
            this.timeSinceKeyPress = 0;

            this.interval = setInterval(function () {
                that.timeSinceKeyPress++;
                if (that.timeSinceKeyPress > 1) {
                    that.populateOptions();
                    clearInterval(that.interval);
                }
            }, 500);
        },


        /**
         *
         */
        populateOptions: function () {
            //fill the dropdown
            $.event.trigger('show-loading');
            this.$http.get(this.url + '?filter=' + this.chosenOption.title, function (response) {
                    this.autocompleteOptions = response;
                    this.showDropdown = true;
                    this.currentIndex = 0;
                    $.event.trigger('hide-loading');
                })
                .error(function (response) {
                    HelpersRepository.handleResponseError(response);
                });
        },

        /**
         *
         */
        respondToEnter: function () {
            if (this.showDropdown) {
                //enter is for the autocomplete
                this.selectOption();
            }
            else {
                //enter is to add the entry
                this.insertItemFunction();
            }
        },

        /**
         *
         * @param index
         */
        selectOption: function (index) {
            if (index) {
                //Item was chosen by clicking
                this.currentIndex = index;
            }
            this.chosenOption = this.autocompleteOptions[this.currentIndex];
            this.showDropdown = false;
            if (this.idToFocusAfterAutocomplete) {
                var that = this;
                setTimeout(function () {
                    $("#" + that.idToFocusAfterAutocomplete).focus();
                }, 100);
            }
            this.$dispatch('option-chosen', this.chosenOption);
        },

        /**
         *
         * @param index
         */
        hoverItem: function(index) {
            this.currentIndex = index;
        },

        /**
         *
         * @param response
         */
        handleResponseError: function (response) {
            $.event.trigger('response-error', [response]);
            this.showLoading = false;
        }
    },
    props: [
        'url',
        'autocompleteField',
        'autocompleteFieldId',
        'insertItemFunction',
        'idToFocusAfterAutocomplete'
    ],
    ready: function () {

    }
});
