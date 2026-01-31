<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Products extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'name',
        'description',
        'image',
        'image_id',
        'price',
        'stock',
        'user_id',
        'category_id',
    ];

    public function reviews()
    {
        return $this->hasMany(Reviews::class,'product_id');
    }
    
    public function transactions()
    {
        return $this->hasMany(Transactions::class, 'product_id');
    }

    public function category()
    {
        return $this->belongsTo(Categories::class, 'category_id');
    }

}
