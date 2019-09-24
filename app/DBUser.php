<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DBUser extends Model
{
    protected $table = 'dbuser_access';

    protected $fillable = [
        'server_id', 'user', 'host', 'user_id', 'expire',
    ];
    public function dbserver()
    {
        
        return $this->belongsTo('App\DBServer');
    }
}
