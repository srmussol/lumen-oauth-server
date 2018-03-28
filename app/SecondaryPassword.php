<?php
/**
 * Created by PhpStorm.
 * User: jaime
 * Date: 21/03/18
 * Time: 14:02
 */

namespace App;


use Illuminate\Database\Eloquent\Model;

class SecondaryPassword extends Model
{
    protected $table = 'secondary_password';

    protected $fillable = [
        'secondary_password', 'user_id'
    ];

    public function user()
    {
        return $this->belongsTo('App\Customer');
    }
}