<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;

    public function users(){
        return $this->belongsToMany(User::class);
    }

    public static function getModule($request){
        if($request->segment(2)=="create"){
            $result='ADD ROLE';
        } elseif($request->segment(3)=="edit"){
            $result='EDIT ROLE';
        } elseif(is_numeric($request->segment(2)) && $request->input('_method')!="DELETE" ){
            $result='VIEW ROLE';
        } elseif(is_numeric($request->segment(2)) && $request->input('_method')=="DELETE" ){
            $result='DELETE ROLE';
        } else {
            $result='ROLE LIST';
        }
        return $result;
    }
    public static function roletablelist(){
        return DB::table('roles')
            ->leftjoin('role_has_permissions','roles.id','=','role_has_permissions.role_id')
            ->leftjoin('permissions', 'role_has_permissions.permission_id', '=', 'permissions.id');
    }
}
