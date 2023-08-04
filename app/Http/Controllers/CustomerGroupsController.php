<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCustomerGroupRequest;
use App\Models\CustomerGroup;
use App\Models\CustomerCategory;
use App\Models\CompanySetting;
use App\Models\customerGroupsCustomer;
use App\Serialization;
use Illuminate\Http\Request;

class CustomerGroupsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $filters = request()->all();
        $customerGroups = CustomerGroup::searchCustomerGroupsWithFilters($filters);
        if(isset($filters['searchvalue'])){
            $customerGroups->withPath('?searchvalue='.($filters['searchvalue']) ? $filters['searchvalue'] : '');
        }

        return view('customer_groups.index', compact('customerGroups','filters'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data = [
            'categorylist' => CustomerCategory::get(),
            'companylist' => CompanySetting::get()
        ];

        return view('customer_groups.create', compact('data'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCustomerGroupRequest $request)
    {
        $hidAction = $request->input("hidAction");

        if ($hidAction === "uploadcfg") {
            return $this->handleUploadCfgAction($request);
        } elseif ($hidAction === "savecfg") {
            return $this->handleSaveCfgAction($request);
        } elseif ($hidAction === "deletecfg") {
            return $this->handleDeleteCfgAction($request);
        }

        return $this->createNewCustomerGroup($request);
    }

    private function handleUploadCfgAction($request)
    {
        if ($_FILES["cfg_file"] && is_uploaded_file($_FILES["cfg_file"]['tmp_name'])) {
            if(!file_exists(public_path()."/cfg/consolidate/".$request->input("id"))){
                @mkdir(public_path()."/cfg/consolidate/".$request->input("id"));
            }
            $temfile_path=public_path()."/cfg/consolidate/".$request->input("id")."/".$request->input("catg")."_TEMP.CFG";
            @unlink($temfile_path);
            move_uploaded_file($_FILES["cfg_file"]['tmp_name'], $temfile_path);

            $obj_serial = new Serialization();
            $arr_ret = $obj_serial->New_DecP($temfile_path, "", "", "", "", "", "", "", "", "", "");
            if(!$arr_ret){
                $arr_return["msg"]="Invalid CFG File";
            } else if($arr_ret["M_Comp"]!=$request->input("compnam")){
                $arr_return["msg"]="Invalid Group Name! This CFG file is for Customer Group ".$arr_ret["M_Comp"];
            } else if($arr_ret["M_Sys_Nam"]!=$request->input("catg")){
                $arr_return["msg"]="Invalid System ID! This CFG file is for System ID ".$arr_ret["M_Sys_Nam"];
            } else {
                $arr_return["exp_dat"] = $arr_ret["M_Exp_Dat"];
                $arr_return["serial_no"] = $arr_ret["M_Chk_Ser"];
                $arr_return["curpassword"] = trim($arr_ret["M_Act_Pass"]);
            }
        } else {
            $arr_return["msg"]="No file been uploaded";
        }

        return $arr_return;
    }

    private function handleSaveCfgAction($request)
    {
        $soft_license = ($request->input('soft_lic')=="")?0:$request->input('soft_lic');
        $category_id = $request->input("category_id");
        $companycode = $request->input("companycode");
        $companyname = $request->input("companyname");
        $system_id = $request->input("system_id");
        $serial_no = $request->input("serial_no");
        $exp_dat = $request->input("exp_dat");
        $curpassword = $request->input("curpassword");
        $newpassword = trim($request->input("newpassword"));

        $temfile_path=public_path()."/cfg/consolidate/".$companycode."/".$system_id."_TEMP.CFG";
        $file_path=public_path()."/cfg/consolidate/".$companycode."/".$system_id.".CFG";
        $bchangepassword=false;
        $a_return["msg"]="Update Serialization Successful";
        $obj_serial = new Serialization();
        if(file_exists($temfile_path)){
            $arr_ret = $obj_serial->New_DecP($temfile_path, "", "", "", "", "", "", "", "", "", "");
            if(!$arr_ret){
                $a_return["msg"]="Invalid CFG File";
            } else if($arr_ret["M_Comp"]!=$companyname){
                $a_return["msg"]="Invalid Group Name! This CFG file is for Customer Group ".$arr_ret["M_Comp"];
            } else if($arr_ret["M_Sys_Nam"]!=$system_id){
                $a_return["msg"]="Invalid System ID! This CFG file is for System ID ".$arr_ret["M_Sys_Nam"];
            } else {
                if($curpassword!=$newpassword){
                    $bchangepassword=true;
                    $cpwfile_path = $temfile_path;
                }
            }
        } else if(file_exists($file_path)){
            $arr_ret = $obj_serial->New_DecP($file_path, "", "", "", "", "", "", "", "", "", "");
            if(!$arr_ret){
                $a_return["msg"]="Invalid CFG File";
            } else if($arr_ret["M_Comp"]!=$companyname){
                $a_return["msg"]="Invalid Group Name! This CFG file is for Customer Group ".$arr_ret["M_Comp"];
            } else if($arr_ret["M_Sys_Nam"]!=$system_id){
                $a_return["msg"]="Invalid System ID! This CFG file is for System ID ".$arr_ret["M_Sys_Nam"];
            } else {
                if($curpassword!=$newpassword){
                    $bchangepassword=true;
                    $cpwfile_path = $file_path;
                }
            }
        } else {
            if(!file_exists(public_path()."/cfg/consolidate/".$companycode)){
                @mkdir(public_path()."/cfg/consolidate/".$companycode);
            }
            $Ra1 = trim($obj_serial->Irand(10,99));
            $Rb1 = trim($obj_serial->Irand(10,99));
            $Rc1 = trim($obj_serial->Irand(10,99));
            $m_fac = "0000000000"; # not in use
            $m_pas = "          "; # System Administrator Password
            $mpas = "0"; # only 2 not for new serialization
            $arr_return = $obj_serial->New_EncP($companyname, $system_id, $exp_dat, $serial_no, $mpas, $m_fac, $m_pas, $temfile_path, $Ra1, $Rb1, $Rc1, $soft_license);
            $newpassword = trim($arr_return["M_Pasw"]);
            $a_return["msg"]="Update Serialization Successful!! New CFG File Created";
            $category = CustomerCategory::find($category_id);
            $category->lastrunno=$serial_no;
            $category->save();
            $a_return["newpassword"] = $newpassword;
        }

        if($bchangepassword){
            $obj_serial = new Serialization();
            $Ra1 = trim($obj_serial->Irand(10,99));
            $Rb1 = trim($obj_serial->Irand(10,99));
            $Rc1 = trim($obj_serial->Irand(10,99));
            $m_fac = "0000000000"; # not in use
            $mpas = "2"; # only 2 not for new serialization
            $arr_return = $obj_serial->New_DecP($cpwfile_path, "", "", "", "", "", "", "", "", "", "");
            $arr_return = $obj_serial->New_EncP($arr_return["M_Pri"].$arr_return["M_Sec"].$arr_return["M_Tet"].$arr_return["M_Frt"].$arr_return["M_Sth"], $system_id, $exp_dat, $serial_no, $mpas, $m_fac, $newpassword, $cpwfile_path, $Ra1, $Rb1, $Rc1, $soft_license);

            $a_return["msg"]="Update Serialization Successful!! CFG Password Changed";
        }

        $group = CustomerGroup::find($request->input("groupid"));
        $group->serial_no = $serial_no;
        $group->exp_dat = $exp_dat;
        $group->cfgpassword = $newpassword;
        $group->cfgfile = "/cfg/consolidate/".$companycode."/".$system_id.".CFG";
        $group->soft_lic = $soft_license;
        $group->save();

        return $a_return;
    }

    private function handleDeleteCfgAction($request)
    {
        $group_id = $request->input("group_id");
        $group = CustomerGroup::find($group_id);
        $category = CustomerCategory::find($group->categoryid);
        $file_path= public_path()."/cfg/consolidate/".$group->groupcode."/".$category->categorycode.".CFG";
        @unlink($file_path);
        $group->serial_no = " ";
        $group->exp_dat = " ";
        $group->cfgpassword = " ";
        $group->agentid = null;
        $group->cfgfile = " ";
        $group->save();
        $a_return["msg"] = "Removed Serialization File Successful!";

        return $a_return;
    }

    private function createNewCustomerGroup($request)
    {
        $companycode = $request->input('groupcode');
        $system_id = $request->input('categorycode');
        $temfile_path = public_path()."/cfg/consolidate/".$companycode."/".$system_id."_TEMP.CFG";
        $file_path = public_path()."/cfg/consolidate/".$companycode."/".$system_id.".CFG";
        if(file_exists($temfile_path)){
            if(file_exists($file_path)){
                @unlink($file_path);
            }
            @rename($temfile_path, $file_path);
        }

        $group = new CustomerGroup();
        $group->groupcode = $request->input('groupcode');
        $group->description = $request->input('description');
        $group->foldername = $request->input('foldername');
        $group->customer_categories_id = $request->input("category_id");
        $group->companyid = $request->input("companyid");
        $group->save();

        $groupid = $group->id;
        if($request->input('cust')){
            foreach($request->input('cust') as $rcust){
                $groupdetail = new customerGroupsCustomer();
                $groupdetail->customer_groups_id = $groupid;
                $groupdetail->customers_id = $rcust;

                $groupdetail->save();
            }
        }

        return redirect('/customer-groups')->with('success', 'New Customer Group ('.$request->input('groupcode').') has been created!');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\CustomerGroup $customerGroup
     * @return \Illuminate\Http\Response
     */
//    public function show(CustomerGroup $customerGroup)
//    {
//        $groupdetail = customerGroupsCustomer::
//                        select('customer_groups_customers.id as id', 'customer_groups_customers.customers_id as customeres_id', 'customers.companyname as companyname', 'customers.companycode as companycode')
//                        ->join('customers', 'customers.id', '=', 'customer_groups_customers.customers_id')
//                        ->where('customer_groups_customers.customer_groups_id', $customerGroup->id)
//                        ->orderBy('companycode', 'ASC')
//                        ->get();
//        $categorylist = CustomerCategory::get();
//        $companylist = CompanySetting::get();
//
//        return view('customer_groups.show', compact('customerGroup', 'groupdetail', 'categorylist', 'companylist'));
//    }
    public function show(CustomerGroup $customerGroup)
    {
        $groupdetail = customerGroupsCustomer::with('customer', 'customerService')
            ->byCategory($customerGroup->customeres_id)
            ->select('customer_groups_customers.id as id', 'customer_groups_customers.customers_id as customeres_id', 'customers.companyname as companyname', 'customers.companycode as companycode')
            ->join('customers', 'customers.id', '=', 'customer_groups_customers.customers_id')
            ->where('customer_groups_customers.customer_groups_id', $customerGroup->id)
            ->orderBy('companycode', 'ASC')
            ->get();

        $categorylist = CustomerCategory::get();
        $companylist = CompanySetting::get();

        return view('customer_groups.show', compact('customerGroup', 'groupdetail', 'categorylist', 'companylist'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function customerList(Request $request)
    {
        $query = Customer::select('id', 'companycode', 'companyname', 'contactperson', 'termid')->orderBy('companycode', 'asc');
        $searchTerm = $request->input("q");
        if (strlen($searchTerm) > 5) {
            $query->where('companyname', 'like', '%' . $searchTerm . '%');
        } else {
            $query->where('companycode', 'like', '%' . $searchTerm . '%');
        }
        $data = $query->get();
        $arr_return = $data->map(function ($rdt) {
            return [
                "value" => $rdt->id,
                "text" => $rdt->companycode . "-" . $rdt->companyname,
            ];
        })->toArray();

        return $arr_return;
    }

    public function custservice(Request $request)
    {
        $serviceId = $request->input("serviceid");
        $customerId = $request->input("customerid");
        $categoryId = $request->input("categoryid");
        if ($serviceId) {
            $data = CustomerService::find($serviceId);
        } else {
            $data = CustomerService::where("customerid", $customerId)
                                    ->where('categoryid', $categoryId)
                                    ->first();
        }
        if (!$data) {
            return response()->json(['error' => 'Data not found'], 404);
        }

        return $data;
    }

    public function savecustservice(Request $request)
    {
        $serviceId = $request->input("serviceid");
        $attributes = $request->only([
            'contract_typ',
            'amount',
            'inc_hw',
            'pay_before',
            'start_date',
            'end_date',
            'service_date',
            'soft_license',
            'vpnaddress',
            'active',
        ]);
        $customerservice = CustomerService::find($serviceId);
        if (!$customerservice) {
            return response()->json(['error' => 'Service not found'], 404);
        }
        $customerservice->update($attributes);

        return array("msg" => "Updated Customer Services!");
    }

}
