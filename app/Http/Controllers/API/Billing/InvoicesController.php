<?php

namespace App\Http\Controllers\API\Billing;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Response;
use Auth;
use Stripe\Customer;
use Stripe\Invoice;
use Stripe\Stripe;

class InvoicesController extends Controller
{
    /**
     * GET /api/invoices
     * @return Response
     */
    public function index(Request $request)
    {
        if ($request->has('upcoming')) {
            Stripe::setApiKey(env('STRIPE_SECRET_KEY'));
            $customer = Customer::retrieve(Auth::user()->stripe_id);

            return Invoice::upcoming(["customer" => $customer->id])->__toArray();
        }

        $invoices = Auth::user()->invoices();

        $array = [];
        foreach ($invoices as $invoice) {
            $array[] = $invoice->getStripeInvoice();
        }
        return response($array, Response::HTTP_OK);
    }
}
