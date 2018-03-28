<?php

namespace App;

use Illuminate\Auth\Authenticatable;
use Laravel\Lumen\Auth\Authorizable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Laravel\Passport\HasApiTokens;
use Log;

class Customer extends Model implements AuthenticatableContract, AuthorizableContract
{
    use HasApiTokens,Authenticatable, Authorizable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'email'
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = ['id'];

    public function secondaryPassword()
    {
        $password = $this->hasMany('App\SecondaryPassword');

        return $password;
    }

    public function validateForPassportPasswordGrant($password)
    {
        $secondaryPasswords = $this->secondaryPassword()->getResults();
        foreach ($secondaryPasswords as $secondaryPassword) {
            $return = $secondaryPassword->secondary_password == $password;
            if ($return === true) return true;
        }

        return false;
    }
    
    public function withAccessToken($accessToken)
    {
        $this->accessToken = $accessToken;

        $token = $this->token();
        $token->scopes = $this->user_scopes;
        $token->save();

        return $this;
    }
}
