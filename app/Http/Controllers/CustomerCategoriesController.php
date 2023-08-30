<?php

namespace App\Http\Controllers;

use App\Http\Requests\CustomerCategoryRequest;
use App\Http\Requests\UpdateCustomerCategoryRequest;
use App\Models\CustomerCategory;
use App\Models\StockCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;

class CustomerCategoriesController extends Controller
{
    /**
     * Display a listing of the customer categories.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Contracts\View\View
     */
    public function index(Request $request)
    {
        $searchValue = $request->input('searchvalue', '');
        $page = $request->input('page', 1);

        $categories = CustomerCategory::search($searchValue);

        $categories->appends(['searchvalue' => $searchValue]);

        return view('customer_categories.index', compact('categories', 'searchValue', 'page'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $stockCategories = StockCategory::pluck('description', 'id');

        return view('customer_categories.create', compact('stockCategories'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\CustomerCategoryRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CustomerCategoryRequest $request)
    {
        $data = $request->validated();
        $data['b_adrmk'] = $request->get('b_adrmk');
        $data['stockcatgid'] = $request->get('stockcatgid');

        $category = CustomerCategory::create($data);
        $arr_post = $this->prepareData($category);

        $this->submitCompUser($arr_post);

        return redirect('/customercategory')->with('success', 'New Customer Category ('.$category->categorycode.') has been created!');
    }

    /**
     * Display the specified resource.
     *
     * @param  CustomerCategory  $category
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Contracts\View\View
     */
    public function show(Request $request, CustomerCategory $customercategory)
    {
        $input = $request->all();

        return view('customer_categories.show', compact('customercategory', 'input'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  CustomerCategory  $category
     * @return \Illuminate\Contracts\View\View
     */
    public function edit(Request $request, CustomerCategory $customercategory)
    {
        $stockCategories = StockCategory::pluck('description', 'id');

        $input = $request->all();

        return View::make('customer_categories.edit', compact('customercategory', 'stockCategories', 'input'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateCustomerCategoryRequest  $request
     * @param  CustomerCategory  $customer_category
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(UpdateCustomerCategoryRequest $request, CustomerCategory $customer_category)
    {
        $data = $request->validated();
        $data['b_adrmk'] = $request->get('b_adrmk');
        $data['stockcatgid'] = $request->get('stockcatgid');

        $customer_category->fill($data)->save();
        $arr_post = $this->prepareData($customer_category, true);
        $this->submitCompUser($arr_post);

        return redirect('/customercategory')->with('success', 'Customer Category (' . $customer_category->categorycode . ') has been updated!');
    }

    protected function prepareData($category, $edit = false)
    {
        $arr_post["mode"] = $edit ? "edit" : "add";
        $arr_post["type"] = "category";
        $arr_post["id"] = $category->id;
        $arr_post["categoryid"] = $category->categoryid;
        $arr_post["categorycode"] = $category->categorycode;
        $arr_post["description"] = $category->description;
        $arr_post["lastrunno"] = $category->lastrunno;
        $arr_post["version"] = $category->version;
        $arr_post["b_rmk"] = $category->b_rmk;
        $arr_post["b_mobapp"] = $category->b_mobapp;
        $arr_post["b_adrmk"] = $category->b_adrmk;
        $arr_post["stockcatgid"] = $category->stockcatgid;
        $arr_post["created_at"] = $category->created_at;
        $arr_post["updated_at"] = $category->updated_at;

        return  $arr_post;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param App\Models\CustomerCategory $customercategory
     */
    public function destroy(CustomerCategory $customercategory)
    {
        $customercategory->delete();

        return redirect('/customercategory')->with('success', 'Customer Category ('.$customercategory->categorycode.') has been deleted!!');
    }

    /**
     * Show the form for uploading a system for the specified resource.
     *
     * @param  int  $id
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Contracts\View\View
     */
    public function uploadsystem(Request $request, $id)
    {
        return view('customer_categories.upload-system', [
            'category' => CustomerCategory::findOrFail($id),
            'id' => $id,
            'system_version' => ((date("Y") - 2015) + 1) . ".0." . date("j.n"),
        ]);
    }

    /**
     * Submit COMP User data.
     *
     * @param  array  $arr_post
     * @return void
     */
    private function submitCompUser($arr_post)
    {
        $authData = [
            'username' => 'bwerp',
            'password' => 'TgG234hgbJH54HB344gbHfWgv',
            'client_id' => 'BWERP',
            'client_secret' => 'EojnU33J2J90MOJ9o340',
            'grant_type' => 'password',
        ];

        // Authenticate and get access token
        $authResponse = $this->makeCurlRequest('https://liveupdate.brightwin.com/liveapi/TOKEN', $authData);

        if ($authResponse !== false) {
            $authData = json_decode($authResponse, true);
            $arr_post['access_token'] = $authData['access_token'];

            // Update COMP data
            $this->makeCurlRequest('https://liveupdate.brightwin.com/liveapi/UPDATECOMP', $arr_post);
        }
    }

    /**
     * Make a cURL request to the given URL with data.
     *
     * @param  string  $url
     * @param  array  $data
     * @return mixed
     */
    private function makeCurlRequest($url, $data)
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => 1,
            CURLOPT_HEADER => 0,
            CURLOPT_URL => $url,
            CURLOPT_POSTFIELDS => http_build_query($data),
            CURLOPT_CONNECTTIMEOUT => 3600,
            CURLOPT_TIMEOUT => 3600,
            CURLOPT_SSL_VERIFYHOST => 0,
        ));

        $response = curl_exec($curl);
        curl_close($curl);

        return $response;
    }

}
