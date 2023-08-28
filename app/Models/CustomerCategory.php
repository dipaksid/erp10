<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerCategory extends Model
{
    use HasFactory;

    protected $fillable = ['categorycode', 'description','lastrunno','b_rmk','b_mobapp','b_adrmk','stockcatgid'];
    public function saveCustomerCategory($data)
    {
        $this->categorycode = $data['categorycode'];
        $this->description = $data['description'];
        $this->lastrunno = $data['lastrunno'];
        $this->b_rmk = $data['b_rmk'];
        $this->b_mobapp = $data['b_mobapp'];
        $this->b_adrmk = $data['b_adrmk'];
        $this->stockcatgid = $data['stockcatgid'];
        $this->save();

        return 1;
    }
    public function updateCustomerCategory($data)
    {
        $customercategory = $this->find($data['id']);
        $customercategory->categorycode = $data['categorycode'];
        $customercategory->description = $data['description'];
        $customercategory->lastrunno = $data['lastrunno'];
        $customercategory->b_rmk = $data['b_rmk'];
        $customercategory->b_mobapp = $data['b_mobapp'];
        $customercategory->b_adrmk = $data['b_adrmk'];
        $customercategory->stockcatgid = $data['stockcatgid'];
        $customercategory->save();

        return 1;
    }
    public static function getModule($request){
        if($request->segment(2)=="create"){
            $result='ADD CUSTOMER CATEGORY';
        } elseif($request->segment(3)=="edit"){
            $result='EDIT CUSTOMER CATEGORY';
        } elseif(is_numeric($request->segment(2)) && $request->input('_method')!="DELETE" ){
            $result='VIEW CUSTOMER CATEGORY';
        } elseif(is_numeric($request->segment(2)) && $request->input('_method')=="DELETE" ){
            $result='DELETE CUSTOMER CATEGORY';
        } else {
            $result='CUSTOMER CATEGORY LIST';
        }

        return $result;
    }
}
