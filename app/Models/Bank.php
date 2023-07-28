<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bank extends Model
{
    use HasFactory;

    protected $fillable = ['bankcode', 'name'];

    public static function getModule($request) {
        if($request->segment(2) == "create") {
            $result='ADD BANK';
        } elseif($request->segment(3) == "edit") {
            $result='EDIT BANK';
        } elseif(is_numeric($request->segment(2)) && $request->input('_method') != "DELETE" ) {
            $result='VIEW BANK';
        } elseif(is_numeric($request->segment(2)) && $request->input('_method') == "DELETE" ) {
            $result='DELETE BANK';
        } else {
            $result='BANK LIST';
        }

        return $result;
    }
}
