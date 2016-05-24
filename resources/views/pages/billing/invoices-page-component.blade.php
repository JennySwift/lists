<script id="invoices-page-template" type="x-template">

<div>

    <div v-if="upcomingInvoice.date">
        <h2>Upcoming Invoice</h2>
        <table class="table table-bordered">
            <tr>
                <th>Date</th>
                <th>Amount</th>
            </tr>
            <tr>
                <td>@{{ upcomingInvoice.date | formatDate }}</td>
                <td>@{{ upcomingInvoice.total  | centsToDollars }}</td>
            </tr>
        </table>
    </div>
    <h3 v-else>No upcoming invoices</h3>

    <div v-if="invoices.length > 0">
        <h2>Invoices</h2>
        <table class="table table-bordered">
            <tr>
                <th>Date</th>
                <th>Amount</th>
                <th>Status</th>
                {{--<th>View</th>--}}
            </tr>
            <tr v-for="invoice in invoices">
                <td>@{{ invoice.date | formatDate }}</td>
                <td>@{{ invoice.total  | centsToDollars }}</td>
                <td v-if="invoice.paid">Paid</td>
                <td v-else>Unpaid</td>
                {{--<td><a href="/api/invoices/@{{ invoice.id }}">Download</a></td>--}}
            </tr>
        </table>
    </div>
    <h3 v-else>No invoices</h3>


</div>

</script>