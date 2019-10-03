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

        $array[] = ['Server', 'Count'];
        foreach($users as $key => $value){
            $array[++$key] = [$value->name, $value->count];
        }
        return view('home')->with('users', json_encode($array));
    }
}
