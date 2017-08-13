<template>
    <div id="feedback">

        <div
            v-for="(feedback, index) in feedbackMessages" v-bind:key="index"
            :class="feedback.type"
            class="feedback-message"
        >

            <ul>
                <li v-for="message in feedback.messages">
                    {{ message }}
                </li>
            </ul>
        </div>
    </div>
</template>

<script>
    var _ = require('underscore');

    export default {
        template: "#feedback-template",
        data: function () {
            return {
                feedbackMessages: []
            };
        },
        methods: {

            /**
             *
             * @param messages
             * @param type
             */
            provideFeedback: function (messages, type) {
                console.log('\n\n messages: ' + messages + '\n\n');
                console.log('\n\n type: ' + type + '\n\n');
                if (typeof messages === 'string') {
                    messages = [messages];
                }

                var feedback = {
                    messages: messages,
                    type: type
                };

                var that = this;

                this.feedbackMessages.push(feedback);

                setTimeout(function () {
                    that.feedbackMessages = _.without(that.feedbackMessages, feedback);
                }, 4000);
            },

            /**
             *
             * @param data
             * @param status
             * @returns {*}
             */
            handleResponseError: function (response) {
                var messages = [];
                var defaultMessage = 'There was an error';

                switch(response.status) {
                    case 503:
                        messages.push('Sorry, application under construction. Please try again later.');
                        break;
                    case 401:
                        messages.push('You are not logged in');
                        break;
                    case 422:
                        messages = this.setMessagesFrom422Status(data);
                        break;
                    default:
                        response && response.error ? messages.push(response.error) : messages.push(defaultMessage);
                        break;
                }

                if (messages.length < 1) {
                    messages.push(defaultMessage);
                }

                return messages;
            },

            /**
             *
             * @returns {string}
             */
            setMessagesFrom422Status: function (data) {
                var messages = [];

                for (errors in data) {
                    for (var i = 0; i < data[errors].length; i++) {
                        messages.push(data[errors][i]);
                    }
                }

                return messages;
            }
        },
        created: function () {
            var that = this;
            this.$bus.$on('provide-feedback', this.provideFeedback);
            this.$bus.$on('response-error', function (response) {
                that.provideFeedback(that.handleResponseError(response), 'error')
            });
        },
        ready: function () {

        },
    }
</script>

