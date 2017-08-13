var expect = require('chai').expect;
var Vue = require('vue');


describe('feedback component', function () {
    var vm;

    beforeEach(function () {
        vm = new Vue(require('../index'));
        expect(vm.feedbackMessages).to.eql([]);
    });

    it('can provide a feedback message', function () {
        vm.provideFeedback('Hello', 'success');

        expect(vm.feedbackMessages).to.eql([
            {
                messages: [
                    'Hello'
                ],
                type: 'success'
            }
        ]);
    });

    it('can provide a feedback message that is an array', function () {
        var messages = [
            'The name field is required.',
            'The type field is required.'
        ];

        vm.provideFeedback(messages, 'error');

        expect(vm.feedbackMessages).to.eql([
            {
                messages: [
                    'The name field is required.',
                    'The type field is required.'
                ],
                type: 'error'
            }
        ]);
    });

    it('can handle an unknown error and provide a default error message', function () {
        var messages = vm.handleResponseError();

        expect(messages).to.eql([
            'There was an error'
        ]);
    });

    it('can handle an error if given the response status', function () {
        var messages = vm.handleResponseError(undefined, 503);

        expect(messages).to.eql([
            'Sorry, application under construction. Please try again later.'
        ]);
    });

    it('can handle a 401 status', function () {
        var messages = vm.handleResponseError(undefined, 401);

        expect(messages).to.eql([
            'You are not logged in'
        ]);
    });

    it('can handle a 422 status', function () {
        var errors = {
            name: ["The name field is required."],
            type: ["The type field is required."]
        };

        var messages = vm.handleResponseError(errors, 422);

        expect(messages).to.eql([
            'The name field is required.',
            'The type field is required.'
        ]);
    });
});