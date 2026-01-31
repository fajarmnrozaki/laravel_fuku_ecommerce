<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Reviews extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'point',
        'content',
        'product_id',
        'user_id',
    ];

    /**
     * Review belongs to a User (owner of the review)
     */
    public function owner()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Review belongs to a Product
     */
    public function product()
    {
        return $this->belongsTo(Products::class, 'product_id');
    }
}
