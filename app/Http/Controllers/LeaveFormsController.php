<?php

namespace App\Http\Controllers;

use App\Models\CompanySetting;
use App\Models\LeaveForm;
use App\Models\Staff;
use App\Models\User;
use App\Models\SystemSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use TCPDF;

class LeaveFormsController extends Controller
{
    const ITEMS_PER_PAGE = 15;
    public function index(Request $request)
    {
        $getUsername = Auth::user()->name;
        $isAdmin = Auth::user()->hasRole('ADMINISTRATOR');

        $query = LeaveForm::query();

        if ($request->has('searchvalue')) {
            $searchValue = $request->input('searchvalue');
            if ($isAdmin) {
                $query->forAdmin($searchValue);
            } else {
                $query->forUser($getUsername, $searchValue);
            }
        } elseif (!$isAdmin) {
            $query->forUser($getUsername, '');
        }

        $leaveform = $query->paginate(self::ITEMS_PER_PAGE);
        $input = $request->all();

        return view('leave_forms.index', compact('leaveform', 'input'));
    }

    public function leaveformpdf($id, Request $request)
    {
        $leaveform = LeaveForm::find($id);

        if (!$leaveform) {
            return view('report.norecord');
        }

        $systemSetting = SystemSetting::first();
        $getApplyUser = Staff::where('name', $leaveform->staff_name)->first();
        $companySetting = CompanySetting::where("b_default", "Y")->first();

        $companyid = $getApplyUser ? $getApplyUser->comp_id : $companySetting->id;
        $dateNow = $request->session()->get('login_date');

        $data = [
            'systemsetting' => $systemSetting,
            'data' => $leaveform,
            'leaveform' => $leaveform,
            'compid' => $companyid,
            'date_now' => $dateNow,
            'request' => $request,
        ];

        // Load the view into a variable
        $view = view('leave_forms.leave_form_pdf', $data)->render();
        $pdf = new TCPDF();
        $pdf->AddPage();

        // Set content
        $pdf->writeHTML($view, true, false, true, false, '');

        // Get the PDF content as a string
        $pdfContent = $pdf->Output('', 'S');

        // Send the PDF as a downloadable stream
        return response($pdfContent)
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'inline; filename="leaveform.pdf"');

    }

    public function create()
    {
        $viewData = [
            'userStaffInfo' => User::userStaffInfo(Auth::user()->id)->first(),
            'loggedInUserName' => Auth::user()->name,
            'systemSetting' => SystemSetting::first(),
        ];

        return view('leave_forms.create', compact('viewData'));
    }


}
