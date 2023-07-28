<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCompanySettingRequest;
use App\Models\Area;
use App\Models\Bank;
use App\Models\CompanySetting;
use App\Traits\FileUploadTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CompanySettingsController extends Controller
{
    use FileUploadTrait;
    /**
     * Display a listing of the resource.
     *
     * @param Request $request The incoming HTTP request object containing user data.
     *
     * @return \Illuminate\View\View The view displaying the list of company settings.
     */
    public function index(Request $request)
    {
        $filters = $request->only(['searchvalue']);
        $companySettings = CompanySetting::searchCompanySettingWithFilters($filters);
        if(isset($filters['searchvalue'])) {
            $companySettings->withPath('?searchvalue='. ($filters['searchvalue']) ? $filters['searchvalue'] : "");
        }

        return view('company_settings.index',compact('companySettings','filters'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View The view displaying the list of company settings.
     */
    public function create()
    {
        $data = $this->getData();
        $default["logindate"] = request()->session()->get('login_date');

        return view('company_settings.create',compact('data','default'));
    }

    /**
     * Store a newly created CompanySetting record in the database.
     *
     * @param \App\Http\Requests\StoreCompanySettingRequest $request The incoming HTTP request containing the form data.
     *
     * @return \Illuminate\Http\RedirectResponse Redirects to the index route for CompanySettings with a success message.
     */
    public function store(StoreCompanySettingRequest $request)
    {
        $validatedData = $request->validated();
        $postData      = $this->preparePostData($validatedData);
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        $companySetting = CompanySetting::create($postData);
        if(isset($companySetting) && $companySetting->id){
            $companyltrheaderfileName = $this->uploadFile($request, 'companyltrheader', "company/{$companySetting->id}", 'bwheader.jpg');
            $companyltrfooterFileName = $this->uploadFile($request, 'companyltrfooter', "company/{$companySetting->id}", 'bwfooter.jpg');
        }
        if(isset($companyltrheaderfileName) && isset($companyltrfooterFileName)) {
            $companySetting->update(['companyltrheader'=>$companyltrheaderfileName, 'companyltrfooter'=>$companyltrfooterFileName]);
        }
        DB::statement('SET FOREIGN_KEY_CHECKS=1');

        return redirect()->route('company_settings.index')->with('success','Permission'. $companySetting->companyname.' added!');
    }

    /**
     * Display the specified resource.
     */
    public function show(CompanySetting $companySetting)
    {
        $data = $this->getData();
        $default["logindate"] = request()->session()->get('login_date');

        return view('company_settings.show', compact('companySetting', 'data','default'));
    }

    /**
     * Display the specified CompanySetting record.
     *
     * @param \App\Models\CompanySetting $companySetting The CompanySetting model instance representing the record to be shown.
     *
     * @return \Illuminate\View\View The view displaying the details of the CompanySetting record.
     */
    public function edit(CompanySetting $companySetting)
    {
        $data = $this->getData();
        $default["logindate"] = request()->session()->get('login_date');

        return view('company_settings.edit', compact('companySetting', 'data','default'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StoreCompanySettingRequest $request, CompanySetting $companySetting)
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
    protected function getData() {
        $data["area"] = Area::where('isactive', 1)->get();
        $data["bank"] = Bank::get();

        return $data;
    }

    protected function preparePostData($validatedData){
        $companySettings = array(
            'companycode' => $validatedData['companycode'],
            'companyname' => $validatedData['companyname'],
            'registrationno' => $validatedData['registrationno'],
            'registrationno2' => $validatedData['registrationno2'],
            'gstno' => $validatedData['gstno'],
            'banks_id' => $validatedData['bankid1'] ?? 0,
            'banks_id2' => $validatedData['bankid2'] ?? 0,
            'bankacc1' => $validatedData['bankacc1'],
            'bankacc2' => $validatedData['bankacc2'],
            'address1' => $validatedData['address1'],
            'address2' => $validatedData['address2'],
            'address3' => $validatedData['address3'],
            'address4' => $validatedData['address4'],
            'city' => $validatedData['city'],
            'zipcode' => $validatedData['zipcode'],
            'area_id' => $validatedData['areaid'] ?? null,
            'contactperson' => $validatedData['contactperson'],
            'contactperson2' => $validatedData['contactperson2'],
            'phoneno1' => $validatedData['phoneno1'],
            'phoneno2' => $validatedData['phoneno2'],
            'email' => $validatedData['email'],
            'email2' => $validatedData['email2'],
        );

        if(isset($validatedData["b_default"])){
            $companySettings['b_default'] = "Y";
        } else {
            $companySettings['b_default'] = "N";
        }

        return $companySettings;
    }
}
