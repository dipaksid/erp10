<?php

namespace App\Http\Controllers;

use App\Http\Requests\SupplierRequest;
use App\Models\Supplier;
use App\Models\Area;
use App\Models\Term;
use Illuminate\Http\Request;
use PhpParser\Node\Expr\Array_;

class SuppliersController extends Controller
{
    const ITEMS_PER_PAGE = 15;
    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $searchValue = $request->input('searchvalue');

        $suppliers = Supplier::with('areas')
            ->when($searchValue, function ($query) use ($searchValue) {
                $query->search($searchValue);
            })
            ->paginate(self::ITEMS_PER_PAGE);

        $suppliers->withPath('?searchvalue=' . ($searchValue ?: ''));

        return view('suppliers.index', compact('suppliers', 'searchValue'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data = $this->getData();

        return view('suppliers.create', compact('data'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  SupplierRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(SupplierRequest $request)
    {
        $validatedData = $request->validated();

        dd($validatedData);

        $supplier = new Supplier();

        if (empty($validatedData['companycode'])) {
            $prefixName = substr($validatedData['companyname'], 0, 1);
            $lastCode = Supplier::whereRaw('substr(companyname, 1, 1) = ?', $prefixName)->max('companycode');

            if ($lastCode !== null) {
                $lastCode = (int) substr($lastCode, -4) + 1;
            } else {
                $lastCode = 1;
            }

            $validatedData['companycode'] = $prefixName . sprintf("%04d", $lastCode);
        }

        // Temporary hardcode for currency control (1=MYR)
        $validatedData['currencyid'] = 1;

        $supplier->fill(array_filter($validatedData, 'strlen'))->save();

        return redirect('/supplier')->with('success', 'New Supplier Code (' . $supplier->companycode . ') has been created!');
    }


    /**
     * Get the Area and Term data
     *
     * @return array
     */
    private  function getData(){
        $data["area"] = Area::where('isactive', 1)->get();
        $data["term"] = Term::get();

        return $data;
    }

}
