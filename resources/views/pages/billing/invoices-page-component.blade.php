<script id="invoices-page-template" type="x-template">

<div>
    <h1>Invoices</h1>

    <table class="table table-bordered">
        <tr>
            <th>Date</th>
            <th>Amount</th>
            <th>Status</th>
            <th>View</th>
        </tr>
        <tr v-for="invoice in invoices">
            <td>@{{ invoice.date | formatDate }}</td>
            <td>@{{ invoice.amount_due  | centsToDollars }}</td>
            <td v-if="invoice.paid">Paid</td>
            <td v-else>Unpaid</td>
            <td></td>
        </tr>
    </table>
</div>

</script>