<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\User as UserEloquent;

class SocialUser extends Model
{
    protected $fillable = [
        'provider_user_id', 'provider', 'user_id'
    ];

    public function user(){
        return $this->belongsTo(UserEloquent::class);
    }
}
