<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Uom extends Model
{
    use HasFactory;

    protected $fillable = ['stockid', 'uomcode', 'description', 'isactive'];

    public static function getModule($request){
        if($request->segment(2)=="edit"){
            $result='EDIT UOMS';
        } elseif($request->segment(2)=="" && $request->method()=="POST"){
            $result='ADD UOMS';
        } elseif(is_numeric($request->segment(2)) && ($request->method()=="PATCH" || $request->method()=="PUT")){
            $result='EDIT UOMS';
        } elseif(is_numeric($request->segment(2)) && $request->input('_method')!="DELETE" ){
            $result='VIEW UOMS';
        } elseif(is_numeric($request->segment(2)) && $request->input('_method')=="DELETE" ){
            $result='DELETE UOMS';
        } else {
            $result='UOMS LIST';
        }
        return $result;
    }

    public function uomsstock(){
        //return $this->belongsToMany('App\SalesInvoiceDetails', 'salesinvoices', 'id', 'invoiceid');
        return $this->belongsTo('App\Stock', 'stockid', 'id');
    }
}
