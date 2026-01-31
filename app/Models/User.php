<?php

// namespace App\Models;

// // use Illuminate\Contracts\Auth\MustVerifyEmail;
// use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Illuminate\Foundation\Auth\User as Authenticatable;
// use Illuminate\Notifications\Notifiable;
// use Illuminate\Database\Eloquent\Concerns\HasUuids;
// use Laravel\Sanctum\HasApiTokens;

// class User extends Authenticatable
// {
//     /** @use HasFactory<\Database\Factories\UserFactory> */
//      use HasApiTokens, HasFactory, Notifiable, HasUuids;

//     /**
//      * The attributes that are mass assignable.
//      *
//      * @var list<string>
//      */
//     protected $fillable = [
//         'name',
//         'email',
//         'password',
//         'role_id',
//     ];

//     /**
//      * The attributes that should be hidden for serialization.
//      *
//      * @var list<string>
//      */
//     protected $hidden = [
//         'password',
//         'remember_token',
//     ];

//     /**
//      * Get the attributes that should be cast.
//      *
//      * @return array<string, string>
//      */
//     protected function casts(): array
//     {
//         return [
//             'email_verified_at' => 'datetime',
//             'password' => 'hashed',
//         ];
//     }
// }

// namespace App\Models;

// // use Illuminate\Contracts\Auth\MustVerifyEmail;
// use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Illuminate\Foundation\Auth\User as Authenticatable;
// use Illuminate\Notifications\Notifiable;
// use Illuminate\Database\Eloquent\Concerns\HasUuids;
// use Laravel\Sanctum\HasApiTokens;

// class User extends Authenticatable
// {
//     /** @use HasFactory<\Database\Factories\UserFactory> */
//     use HasApiTokens, HasFactory, Notifiable, HasUuids;

//     /**
//      * The attributes that are mass assignable.
//      *
//      * @var list<string>
//      */
//     protected $fillable = [
//         'name',
//         'email',
//         'password',
//         'role_id',
//     ];

//     /**
//      * The attributes that should be hidden for serialization.
//      *
//      * @var list<string>
//      */
//     protected $hidden = [
//         'password',
//         'remember_token',
//     ];

//     /**
//      * Get the attributes that should be cast.
//      *
//      * @return array<string, string>
//      */
//     protected function casts(): array
//     {
//         return [
//             'email_verified_at' => 'datetime',
//             'password' => 'hashed',
//         ];
//     }

//     /*
//     |--------------------------------------------------------------------------
//     | ELOQUENT RELATIONSHIPS
//     |--------------------------------------------------------------------------
//     */

//     // USER → Profile (1-to-1)
//     public function profile()
//     {
//         return $this->hasOne(Profile::class);
//     }

//     // USER → Role (Many users belong to one role)
//     public function role()
//     {
//         return $this->belongsTo(Role::class);
//     }

//     // USER → Reviews (1 user can create many reviews)
//     public function reviews()
//     {
//         return $this->hasMany(Review::class);
//     }

//     // USER → Transactions (1 user has many transactions)
//     public function transactions()
//     {
//         return $this->hasMany(Transactions::class);
//     }
// }

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Laravel\Sanctum\HasApiTokens;
use App\Models\Roles;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasUuids;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role_id',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // User belongs to a Role
    public function role()
    {
    return $this->belongsTo(Roles::class, 'role_id', 'id');
    }

    // User has many Reviews
    public function reviews()
    {
        return $this->hasMany(Reviews::class, 'user_id');
    }

    // User has many Transactions
    public function transactions()
    {
        return $this->hasMany(Transactions::class, 'user_id');
    }

    // User has one relation to onne OTP code
    public function otpcode()
    {
        return $this->hasOne(OtpCode::class, 'user_id');
    }

}
