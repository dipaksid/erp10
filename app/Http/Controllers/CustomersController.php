<?php

namespace App\Http\Controllers;

use App\Models\Area;
use App\Models\Customer;
use App\Models\CustomerCategory;
use App\Models\Term;
use App\Models\CustomerGroup;
use App\Models\customerGroupsCustomer;
use App\Http\Requests\StoreCustomerRequest;
use App\Http\Requests\UpdateCustomerRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use TCPDF;

class CustomersController extends Controller
{
    const CUSTOMERS_PER_PAGE = 15;
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $filters['searchvalue'] = $request->input('searchvalue');
        $filters['srch_area'] = $request->input('srch_area');
        $customers = Customer::searchCustomer($filters);
        $pdffile = null;
        if ($request->has('btnpdf') && $request->input('btnpdf') !== "") {
            $pdffile = url("/pdf/".$this->generatePdf($customers));
        }
        $input = $request->all();
        $area = Area::get();
        $customers = $customers->paginate(self::CUSTOMERS_PER_PAGE);
        if(isset($filters['searchvalue'])) {
            $customers = $customers->appends(['searchvalue' => $filters['searchvalue']]);
        }
        if(isset($filters['srch_area'])) {
            $customers = $customers->appends(['srch_area' => $filters['srch_area']]);
        }

        return view('customers.index', compact('customers', 'input', 'area', 'pdffile'));
    }

    protected function generatePdf($customers) {
        $pdfdata = clone $customers;
        $arr_data = $pdfdata->get();
        view()->share('data', $arr_data);
        $pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);
        $pdf->SetMargins(10, 10, 10, true);
        $pdf->AddPage();
        $html = \Illuminate\Support\Facades\View::make('customers.customer')->render();
        $pdf->writeHTML($html, true, false, true, false, '');
        $pdfFilename = 'customerlist_' . Str::random(15) . '.pdf';
        $pdf->Output(public_path('pdf/' . $pdfFilename), 'F');

        return $pdfFilename;
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCustomerRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, Customer $customer)
    {
        $data = [
            'start_date' => $customer->start_date_formatted,
            "area" => Area::where('isactive', 1)->get(),
            "category" => CustomerCategory::get(),
            "term" => Term::get(),
            "customer_group" => CustomerGroup::get(),
            "group_detail" => customerGroupsCustomer::where('customers_id', $customer->id)->get(),
        ];
        $input = $request->all();

        return view('customers.show', compact('data', 'customer', 'input'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Customer $customer)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCustomerRequest $request, Customer $customer)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Customer $customer)
    {
        //
    }
}
