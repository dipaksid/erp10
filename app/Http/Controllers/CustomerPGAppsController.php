<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePWSAppRequest;
use App\Http\Requests\UpdateApiOauthUserRequest;
use App\Models\CustomerPwspgapp;
use App\Models\UserDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
class CustomerPGAppsController extends Controller
{
    const ITEMS_PER_PAGE = 15;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $searchValue = $request->input('searchvalue');

        $pwspgapp = CustomerPwspgapp::withUserData()
                        ->searchByKeyword($searchValue)
                        ->paginate(self::ITEMS_PER_PAGE);

        $pwspgapp->appends(['searchvalue' => $searchValue]);

        return view('customer_pwspg_apps.index', compact('pwspgapp', 'searchValue'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('customer_pwspg_apps.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  StorePWSAppRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StorePWSAppRequest $request)
    {
        $apioauthscope = UserDetail::selectRaw("group_concat(description SEPARATOR ' ') as scope")
                                        ->where("client_id", "PWSPGAPP")->first();

        $apioauthuser = $this->createApiOauthUser($request, $apioauthscope);

        $this->createCustomerPwspgapps($request, $apioauthuser);

        $this->submitCompApp($apioauthuser);

        return redirect('/customer-pwspg-apps')->with('success', 'New PWS PG App Service has been created!');
    }

    protected function createApiOauthUser(Request $request, $apioauthscope)
    {
        $apioauthuser = new UserDetail($request->only([
            'username',
            'password',
            'mob_pho',
            'first_name',
            'idle_tim',
            'access_pdf',
            'email',
        ]));

        $apioauthuser->scope = $apioauthscope->scope;
        $apioauthuser->client_id = "PWSPGAPP";
        $apioauthuser->save();

        return $apioauthuser;
    }

    protected function createCustomerPwspgapps(Request $request, $apioauthuser)
    {
        foreach ($request->input("cust") as $ckey => $custid) {
            $pwspgapp = new CustomerPwspgapp([
                'customerid' => $custid,
                'userid' => $apioauthuser->id,
                'apiurl' => $request->input('apiurl')[$ckey],
                'client_id' => "PGAPP",
                'client_secret' => "H2WeWtqBFjWRCAFyvD30",
                'username' => "USER",
                'password' => "2WtAJxo2fP9sLX81j4GE",
                'active' => "Y",
            ]);
            $pwspgapp->save();

            $customersinfo = Customer::find($custid);
            $arr_post["cid"][$ckey]             = $pwspgapp->id;
            $arr_post["customerid"][$ckey]      = $custid;
            $arr_post["companyname"][$ckey]     = $customersinfo->companyname;
            $arr_post["apiurl"][$ckey]          = $request->input('apiurl')[$ckey];
            $arr_post["sclient_id"][$ckey]      = "PGAPP";
            $arr_post["sclient_secret"][$ckey]  = "H2WeWtqBFjWRCAFyvD30";
            $arr_post["susername"][$ckey]       = "USER";
            $arr_post["spassword"][$ckey]       = "2WtAJxo2fP9sLX81j4GE";
            $arr_post["active"][$ckey]          = "Y";
        }
    }

    protected function submitCompApp($apioauthuser)
    {
        $arr_post = $apioauthuser->toArray();
        // Additional processing if needed
        $this->submitCompAppLogic($arr_post);
    }

    protected function submitCompAppLogic($arr_post)
    {
        $response = Http::post('https://pawnlive.my/pawnapp/TOKEN', [
            'username' => 'bwerp',
            'password' => '4gfdG45HT4vbf4Gh1',
            'client_id' => 'BWERP',
            'client_secret' => 'RFDe5441GgHweYb88',
            'grant_type' => 'password',
        ]);

        if ($response->failed()) {
            return;
        }
        $rdata = $response->json();
        $accessToken = $rdata['access_token'];

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $accessToken,
        ])->post('https://pawnlive.my/pawnapp/COMPANYPGAPP', $arr_post);

        if ($response->failed()) {
            return;
        }
    }

    public function edit(Request $request, $id)
    {
        $customerpwspgapp = CustomerPwspgapp::fetchEditData($id);
        $input = $request->all();

        return view('customer_pwspg_apps.edit', compact('customerpwspgapp', 'id', 'input'));
    }

    public function update(UpdateApiOauthUserRequest $request, CustomerPwspgapp $apioauthuser)
    {
        $data = $request->validated();

        $apioauthscope = CustomerPwspgapp::selectRaw("group_concat(description SEPARATOR ' ') as scope")
            ->where("client_id", "PWSPGAPP")->first();

        $apioauthuser->fill($data);
        $apioauthuser->scope = $apioauthscope->scope;
        $apioauthuser->save();

        $this->updateSalesInvoiceDetail($request, $apioauthuser->id);

        return redirect('/customerpwspgapp')->with('success', 'PWS PG App Service Updated!!');
    }
}
