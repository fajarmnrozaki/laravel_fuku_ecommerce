<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use App\Models\User;


class Roles extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = ['name'];

    protected $hidden = ['created_at', 'updated_at'];

    public function users()
    {
        return $this->hasMany(User::class, 'role_id', 'id');
    }
}
