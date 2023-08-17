<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\CustomerService;
use App\Models\CustomerCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CustomerServicesController extends Controller
{
    const SERVICES_PER_PAGE = 15;

    /**
     * Display a listing of customer services.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $searchValue = $request->input('searchvalue', '');
        $service = CustomerService::searchAndPaginate($searchValue);

        $input = $request->all();
        return view('customer_services.index', compact('service', 'input'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('customer_services.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response|array
     */
    public function store(Request $request)
    {
        $service = new CustomerService();

        $result = $service->storeService($request);

        return $result;
    }

    /**
     * Display the specified resource.
     *
     * @param  App\Models\CustomerService  $customerService
     * @return \Illuminate\Http\Response
     */
    public function show(CustomerService $customerService)
    {
        return view('customer_services.show', compact('customerService'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $id)
    {
        $customer = Customer::findOrFail($id);
        $customerServices = CustomerService::customerservicetablelist()
            ->select([
                'customer_services.*',
                'customers.companycode as companycode',
                'customers.companyname as companyname',
                'agents.id as agentid',
                \DB::raw("concat(agents.agentcode,'-',agents.name) as agentname"),
                'customer_categories.lastrunno as lastrunno'
            ])
            ->join('customers as cust', 'customer_services.customers_id', '=', 'cust.id')
            ->join('agents as ag', 'customer_services.agents_id', '=', 'ag.id')
            ->join('customer_categories as cat', 'customer_services.customer_categories_id', '=', 'cat.id')
            ->where('cust.id', $id)
            ->get();
        $categories = CustomerCategory::all();
        $serviceByCategory = [];
        foreach ($customerServices as $service) {
            $serviceByCategory[$service->customer_categories_id] = $service;
        }
        $input = $request->all();
        $companyCfgPath = public_path("/cfg/{$customer->companycode}");
        if (!file_exists($companyCfgPath)) {
            @mkdir($companyCfgPath);
        }
        foreach ($categories as $rcategory) {
            $file_path = "{$companyCfgPath}/{$rcategory->categorycode}_TEMP.CFG";
            @unlink($file_path);
        }

        return view('customer_services.edit', compact('customer', 'serviceByCategory', 'id', 'categories', 'input'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return array
     */
    public function update(Request $request, $id)
    {
        $service = CustomerService::findOrFail($id);
        $service->fill($request->only([
            'amount', 'contract_typ', 'inc_hw', 'pay_before', 'start_date',
            'end_date', 'service_date', 'soft_license', 'pos_license', 'vpnaddress',
            'active', 'rmk'
        ]));
        $service->save();

        if ($service->soft_license > 0) {
            $custcatg = CustomerCategory::find($request->input('categoryid'));
            $cust = Customer::find($request->input('customerid'));
            $file_path = public_path() . "/cfg/{$cust->companycode}/{$custcatg->categorycode}.CFG";
            if (file_exists($file_path)) {
                $obj_serial = new Serialization();

                $arr_return = $obj_serial->New_DecP($file_path, "", "", "", "", "", "", "", "", "", "");
                $m_fac = "0000000000"; // not in use
                $mpas = "2";
                $Ra1 = trim($obj_serial->Irand(10, 99));
                $Rb1 = trim($obj_serial->Irand(10, 99));
                $Rc1 = trim($obj_serial->Irand(10, 99));

                $arr_return = $obj_serial->New_EncP(
                    $arr_return["M_Pri"] . $arr_return["M_Sec"] . $arr_return["M_Tet"] . $arr_return["M_Frt"] . $arr_return["M_Sth"],
                    $custcatg->categorycode, $arr_return["M_Exp_Dat"], $arr_return["M_Chk_Ser"], $mpas, $m_fac,
                    $arr_return["M_Act_Pass"], $file_path, $Ra1, $Rb1, $Rc1, $service->soft_license
                );
            }
        }
        // Update API Live OAuth user if exists
        $apiliveuser = ApiLiveOauthUser::where("serviceid", $id)->first();
        if ($apiliveuser) {
            $arr_post = [
                "mode" => "edit",
                "active" => $service->active,
                "type" => "customer",
                "id" => $apiliveuser->id,
                "updated_at" => $apiliveuser->updated_at,
            ];
            $this->submitCompUser($arr_post);
        }

        return ["msg" => "Customer Services Updated!"];
    }

    public function agentlist(Request $request)
    {
        if(strlen($request->input("q")) > 5) {
            $data = Agent::select('id','agentcode','name')
                            ->where('name', 'like', '%'.$request->input("q").'%')
                            ->orderBy('name','asc')
                            ->get();
        } else {
            $data = Agent::select('id','agentcode','name')
                            ->where('agentcode', 'like', '%'.$request->input("q").'%')
                            ->orderBy('agentcode','asc')
                            ->get();
        }
        $arr_return=array();
        foreach($data as $rdt){
            array_push($arr_return,array("value"=>$rdt["id"],"text"=>$rdt["agentcode"]."-".$rdt["name"]));
        }

        return $arr_return;
    }
}
