<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    use HasFactory;

    protected $fillable = ['companyname', 'companycode', 'registrationno', 'registrationno2', 'address1', 'address2', 'address3', 'address4', 'contactperson', 'phoneno1', 'phoneno2', 'faxno1', 'faxno2', 'email', 'email2', 'homepage', 'areaid', 'termid', 'status', 'startdate', 'zipcode', 'currencyid'];
    public function saveSupplier($data)
    {
        foreach($data as $key => $val){
            if(in_array($key, $this->fillable)) {
                $this->$key=$val;
            }
        }
        $this->status=(isset($data["status"]))?"ACTIVE":"";
        if(isset($data["startdate"])){
            $this->startdate=Carbon::createFromFormat('d/m/Y', $data["startdate"]);
        }
        $this->save();
        return 1;
    }
    public function updateSupplier($data)
    {
        $supplier = $this->find($data['id']);
        foreach($data as $key => $val){
            if(in_array($key, $this->fillable)) {
                $supplier->$key=$val;
            }
        }
        $supplier->status=(isset($data["status"]))?"ACTIVE":"";
        $supplier->startdate=Carbon::createFromFormat('d/m/Y', $data["startdate"]);
        $supplier->save();
        return 1;
    }

    public function areas(){
        return $this->belongsToMany('App\Models\Area', 'suppliers', 'id', 'areas_id');
    }

    public function area1(){
        return $this->belongsTo('App\Area', 'areaid', 'id');
    }

    public function term(){
        return $this->belongsToMany('App\Term', 'suppliers', 'id', 'termid');
    }

    public static function getModule($request){
        if($request->segment(2)=="create"){
            $result='ADD SUPPLIER';
        } elseif($request->segment(3)=="edit"){
            $result='EDIT SUPPLIER';
        } elseif(is_numeric($request->segment(2)) && $request->input('_method')!="DELETE" ){
            $result='VIEW SUPPLIER';
        } elseif(is_numeric($request->segment(2)) && $request->input('_method')=="DELETE" ){
            $result='DELETE SUPPLIER';
        } else {
            $result='SUPPLIER LIST';
        }
        return $result;
    }

    public function scopeSearch(Builder $query, $searchValue)
    {
        return $query->select('suppliers.id', 'suppliers.companyname', 'areas.description', 'suppliers.companycode', 'suppliers.registrationno', 'suppliers.registrationno2', 'suppliers.contactperson', 'suppliers.phoneno1', 'suppliers.phoneno2', 'suppliers.email')
            ->join('areas', 'suppliers.areaid', '=', 'areas.id')
            ->where(function ($q) use ($searchValue) {
                $q->where('areas.description', 'like', '%' . $searchValue . '%')
                    ->orWhere('suppliers.companyname', 'like', '%' . $searchValue . '%')
                    ->orWhere('suppliers.contactperson', 'like', '%' . $searchValue . '%')
                    ->orWhere('suppliers.phoneno1', 'like', '%' . $searchValue . '%')
                    ->orWhere('suppliers.phoneno2', 'like', '%' . $searchValue . '%')
                    ->orWhere('suppliers.registrationno', 'like', '%' . $searchValue . '%')
                    ->orWhere('suppliers.registrationno2', 'like', '%' . $searchValue . '%')
                    ->orWhere('suppliers.companycode', 'like', '%' . $searchValue . '%')
                    ->orWhere('suppliers.email', 'like', '%' . $searchValue . '%');
            });
    }
}
