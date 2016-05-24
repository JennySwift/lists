<script id="customer-page-template" type="x-template">

<div id="customer-page">
    <h1>Credit Card Info</h1>

    <div class="form-group">
        <label for="card-number">Number</label>
        <input
            v-model="card.number"
            type="text"
            id="card-number"
            placeholder="number"
            class="form-control"
        >
    </div>

    <div class="form-group">
        <label for="card-cvc">CVC</label>
        <input
            v-model="card.cvc"
            type="text"
            id="card-cvc"
            placeholder="cvc"
            class="form-control"
        >
    </div>

    <div class="form-group">
        <label for="card-expiration-month">Expiration Month</label>

        <select
            v-model="card.expirationMonth"
            id="card-expiration-month"
            class="form-control"
        >
            <option
                v-for="month in months"
                v-bind:value="month.value"
            >
                @{{ month.name }}
            </option>
        </select>
    </div>

    <div class="form-group">
        <label for="card-years">Expiration Year</label>

        <select
            v-model="card.expirationYear"
            id="card-years"
            class="form-control"
        >
            <option
                v-for="year in years"
                v-bind:value="year"
            >
                @{{ year }}
            </option>
        </select>
    </div>

    <button v-on:click="generateToken()" class="btn btn-default">Save Details</button>
</div>

</script>