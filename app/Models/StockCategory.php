<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockCategory extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'categorycode', 'description', 'isactive',
    ];

    public static function getModule($request){
        if($request->segment(2)=="create"){
            $result='ADD STOCK CATEGORY';
        } elseif($request->segment(3)=="edit"){
            $result='EDIT STOCK CATEGORY';
        } elseif(is_numeric($request->segment(2)) && $request->input('_method')!="DELETE" ){
            $result='VIEW STOCK CATEGORY';
        } elseif(is_numeric($request->segment(2)) && $request->input('_method')=="DELETE" ){
            $result='DELETE STOCK CATEGORY';
        } else {
            $result='STOCK CATEGORY LIST';
        }
        return $result;
    }

}
