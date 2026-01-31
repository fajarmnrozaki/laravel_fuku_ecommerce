<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Transactions extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'amount',
        'total',
        'status',
        'user_id',
        'product_id',
    ];

    /*
    |--------------------------------------------------------------------------
    | RELATIONSHIPS
    |--------------------------------------------------------------------------
    */

    // each transaction belongs to 1 product
    public function product()
    {
        return $this->belongsTo(Products::class);
    }

    // each transaction belongs to 1 user (owner)
    public function owner()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
