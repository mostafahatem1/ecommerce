<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Mindscms\Entrust\Traits\EntrustUserWithPermissionsTrait;
use Nicolaslopezj\Searchable\SearchableTrait;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable ,EntrustUserWithPermissionsTrait,SearchableTrait;

    public function receivesBroadcastNotificationsOn(): string
    {
        return 'App.Models.User.' . $this->id;
    }

    protected $fillable = [
        'first_name',
        'last_name',
        'username',
        'mobile',
        'user_image',
        'status',
        'email',
        'password',

    ];


    protected $appends = ['full_name'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];


    public $searchable = [
        'columns' => [
            'users.first_name' => 10,
            'users.last_name' => 10,
            'users.username' => 10,
            'users.email' => 10,
            'users.mobile' => 10,
        ]
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];



    public function getFullNameAttribute()
    {
        return ucwords($this->first_name) . ' ' . ucwords($this->last_name);
    }

    public function status(): string
    {
        return $this->status ? 'Active' : 'Inactive';
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(ProductReview::class);
    }
    public function addresses(): HasMany
    {
        return $this->hasMany(UserAddress::class);

    }
    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }


}

