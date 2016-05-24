<script id="subscription-page-template" type="x-template">

<div id="subscription-page">
    <h1>Subscription</h1>

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
    
    
</div>

</script>