<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Agent extends Model
{
    use HasFactory;

    protected $fillable = ['agentcode', 'name', 'commrate', 'areas_id'];

    public static function getModule($request){
        if($request->segment(2)=="create"){
            $result='ADD AGENT';
        } elseif($request->segment(3)=="edit"){
            $result='EDIT AGENT';
        } elseif(is_numeric($request->segment(2)) && $request->input('_method')!="DELETE" ){
            $result='VIEW AGENT';
        } elseif(is_numeric($request->segment(2)) && $request->input('_method')=="DELETE" ){
            $result='DELETE AGENT';
        } else {
            $result='AGENT LIST';
        }
        return $result;
    }

}
