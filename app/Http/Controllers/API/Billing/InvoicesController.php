<?php

namespace App\Http\Controllers\API\Billing;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Response;
use Auth;

class InvoicesController extends Controller
{
    /**
     * GET /api/invoices
     * @return Response
     */
    public function index()
    {
        $invoices = Auth::user()->invoices();

        $array = [];
        foreach ($invoices as $invoice) {
            $array[] = $invoice->getStripeInvoice();
        }
        return response($array, Response::HTTP_OK);
    }
}
