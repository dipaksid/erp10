<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerService extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function customercategory()
    {
        return $this->belongsTo('App\Models\CustomerCategory', 'customer_categories_id', 'id');
    }
    public function customer()
    {
        return $this->belongsTo('App\Models\Customer', 'customers_id', 'id');
    }
}
