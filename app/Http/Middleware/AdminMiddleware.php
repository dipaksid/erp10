<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use App;
use App\Models\User;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if(is_null(Auth::user())) {

            return redirect('/login');
        } else {

            if (!Auth::user()->hasPermissionTo($this->getModule($request))) {
                if($request->segment(1)=="home"){
                    $login_date = $request->session()->get('login_date') ?? now();
                    abort('411', $login_date);
                } else if($request->segment(1)=="") {
					return redirect('/login');//view('auth.login');
				} else {
                    abort('401',$request->session()->get('login_date'));
                }
            }
        }
        view()->share('request',$request);

        return $next($request);
    }

    protected function getModule($request){
        $requestSegment = $request->segment(1);
        switch ($requestSegment) {
            case "salesinvoice":
                $classname = "App\\SalesInvoice";
                break;
            case "customercategory":
                $classname = "App\\CustomerCategory";
                break;
            case "stockcategory":
                $classname = "App\\StockCategory";
                break;
            case "receivepayment":
                $classname = "App\\ReceivePayment";
                break;
            case "paymentvoucher":
                $classname = "App\\PaymentVoucher";
                break;
            case "creditnote":
                $classname = "App\\CreditNote";
                break;
            case "purchaseorder":
                $classname = "App\\PurchaseOrder";
                break;
            case "uom":
                $classname = "App\\UOMs";
                break;
            case "customergroup":
                $classname = "App\\CustomerGroup";
                break;
            case "customerservice":
                $classname = "App\\CustomerService";
                break;
            case "customerpwspgapp":
                $classname = "App\\CustomerPwspgapp";
                break;
            case "systemsetting":
                $classname = "App\\SystemSetting";
                break;
            case "servicesrate":
                $classname = "App\\ServiceRate";
                break;
            case "solutionprofile":
                $classname = "App\\SolutionProfile";
                break;
            case "softwareservice":
                $classname = "App\\SoftwareService";
                break;
            case "serviceform":
                $classname = "App\\ServiceForm";
                break;
            case "totalpayapp":
                $classname = "App\\TotalpayApp";
                break;
            case "companysetting":
                $classname = "App\\CompanySetting";
                break;
            case "softwaresrvupload":
                $classname = "App\\SoftwareService";
                break;
            case "bankdocs":
                $classname = "App\\Bankdoc";
                break;
            case "trainingform":
                $classname = "App\\TrainingForm";
                break;
            case "evaluationform":
                $classname = "App\\EvaluationForm";
                break;
            case "leaveform":
                $classname = "App\\LeaveForm";
                break;
            default:
                $classname = "App\\Models\\" . ucfirst($requestSegment);
                break;
        }

        if (ucfirst($requestSegment) == "") {
            return false;
        } else {
            $method = "getModule";
            return $classname::$method($request);
        }
    }
}
