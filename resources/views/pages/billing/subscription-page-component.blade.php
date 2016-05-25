<script id="subscription-page-template" type="x-template">

<div id="subscription-page">
    <h1>Subscription</h1>

    <h5 v-if="!me.stripe_id">Please enter your credit card details before selecting a subscription plan</h5>

    <div>
        <span>Current Plan: </span>
        <span v-if="me.stripe_plan">@{{ me.stripe_plan }}</span>
        <span v-else>Unsubscribed</span>
    </div>
    
    <div class="form-group">
        <label for="subscription-plan-">Subscription Plans</label>
    
        <select
            v-model="subscriptionPlan"
            id="subscription-plan"
            class="form-control"
        >
            <option
                v-for="plan in subscriptionPlans"
                v-bind:value="plan.id"
            >
                @{{ plan.id }} ($@{{ plan.amount / 100 }})
            </option>
        </select>
    </div>

    <button v-on:click="updateSubscription()" class="btn btn-success">Update Subscription Plan</button>
    <button
        v-if="me.stripe_plan && !me.subscription_ends_at"
        v-on:click="cancelSubscription()"
        class="btn btn-danger"
    >
        Cancel Subscription
    </button>

    <div v-if="me.subscription_ends_at">
        <span>Your subscription has been cancelled but will remain until @{{ me.subscription_ends_at | formatDateTime }}.</span>
        <button v-on:click="resumeSubscription()" class="btn btn-success">Resume Subscription</button>
    </div>

    
</div>

</script>