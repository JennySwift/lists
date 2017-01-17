<template>
    <div>
        <h1>Submit Feedback</h1>

        <div>
            <div class="form-group">
                <label for="new-feedback-title">Title</label>
                <input
                    v-model="newFeedback.title"
                    v-on:keyup.13="submitFeedback()"
                    type="text"
                    id="new-feedback-title"
                    name="new-feedback-title"
                    placeholder="title"
                    class="form-control"
                >
            </div>

            <div class="form-group">
                <label for="new-feedback-body">Body</label>
                <input
                    v-model="newFeedback.body"
                    v-on:keyup.13="submitFeedback()"
                    type="text"
                    id="new-feedback-body"
                    name="new-feedback-body"
                    placeholder="body"
                    class="form-control"
                >
            </div>

            <div class="form-group">
                <button v-on:click="submitFeedback()" class="btn btn-success">Submit</button>
            </div>
        </div>
    </div>
</template>

<script>
    module.exports = {
        template: '#feedback-page-template',
        data: function () {
            return {
                newFeedback: {}
            };
        },
        components: {},
        methods: {

            /**
            * For submitting feedback to my lists app
            */
            submitFeedback: function () {
                var data = {
                    title: this.newFeedback.title,
                    body: this.newFeedback.body,
                    priority: this.newFeedback.priority,
                };

                helpers.post({
                    url: '/api/feedback',
                    data: data,
                    array: 's',
                    message: 'Feedback created',
                    clearFields: this.clearFields,
                    redirectTo: this.redirectTo,
                    callback: function () {
                        this.showPopup = false;
                    }.bind(this)
                });
            },

        },
        props: [
            //data to be received from parent
        ],
        ready: function () {

        }
    };
</script>
