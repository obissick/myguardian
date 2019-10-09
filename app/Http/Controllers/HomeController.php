<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\DBServer;
use App\DBUser;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $servers = DB::table('servers')->select(DB::raw('count(id) as numServers'))->get()->toArray();
        $users = DB::table('dbuser_access')
                ->join('servers', 'servers.id', '=', 'dbuser_access.server_id')
                ->select(DB::raw('servers.name as name, count(dbuser_access.id) as count'))
                ->groupBy('servers.name')->get()->toArray();
        $expired = DB::table('dbuser_access')
                ->join('servers', 'servers.id', '=', 'dbuser_access.server_id')
                ->select(DB::raw('servers.name as name, count(dbuser_access.id) as count'))
                ->where('expired', '=', true)
                ->groupBy('servers.name')->get()->toArray();
        $expiredUsers = DB::table('dbuser_access')
                ->join('servers', 'servers.id', '=', 'dbuser_access.server_id')
                ->select(DB::raw('servers.name as name, user, dbuser_access.host'))
                ->where('expired', '=', true)
                ->get()->toArray();
        $expireSoon = DB::table('dbuser_access')
                ->join('servers', 'servers.id', '=', 'dbuser_access.server_id')
                ->select(DB::raw('servers.name as name, user, dbuser_access.host, expire'))
                ->whereRaw('WEEK(expire) = WEEK(NOW())')
                ->get()->toArray();


        $array[] = ['Server', 'Count'];
        foreach($users as $key => $value){
            $array[++$key] = [$value->name, $value->count];
        }
        $ex[] = ['Server', 'Expired'];
        foreach($expired as $key => $value){
            $ex[++$key] = [$value->name, $value->count];
        }
        $expiredU[] = ['Server', 'User'];
        foreach($expiredUsers as $key => $value){
            $expiredU[++$key] = [$value->name, $value->user.'@'.$value->host];
        }
        $expiring[] = ['Server', 'User', 'Expire Date'];
        foreach($expireSoon as $key => $value){
            $expiring[++$key] = [$value->name, $value->user.'@'.$value->host, $value->expire];
        }
        return view('home')->with('users', json_encode($array))->with('expired', json_encode($ex))->with('expiredUsers', json_encode($expiredU))->with('expiring', json_encode($expiring));
    }
}
