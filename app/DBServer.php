<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DBServer extends Model
{
    protected $table = 'servers';

    protected $fillable = [
        'ip', 'name', 'port', 'username', 'user_id', 'password',
    ];

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function dbuser()
    {
        return $this->hasMany('App\DBUser');
    }
}
