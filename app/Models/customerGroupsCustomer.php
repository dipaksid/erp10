<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class customerGroupsCustomer extends Model
{
    use HasFactory;

    protected $fillable = ['customer_groups_id', 'customers_id'];

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customers_id', 'id');
    }

    public function customerService()
    {
        return $this->belongsTo(CustomerService::class, 'customers_id', 'customers_id');
    }

    public function customergroup()
    {
        return $this->belongsTo(CustomerGroup::class, 'customer_groups_id', 'id');
    }

    public function scopeByCategory($query, $categoryid)
    {
        return $query->whereHas('customerService', function ($q) use ($categoryid) {
            $q->where('customer_categories_id', $categoryid);
        });
    }

}
