<?php

namespace App\Http\Controllers;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\DBServer;
use App\DBUser;

class DBUserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //$servers = DBServer::where('user_id', Auth::user()->id)->dbuser;
        $users = DB::table('servers')
            ->join('dbuser_access', 'servers.id', '=', 'dbuser_access.server_id')
            ->select('dbuser_access.*', 'servers.name')
            ->where('user_id', Auth::user()->id)->get();
            //->paginate(25);
        
        return view('users.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if($request->delete === "on"){
            $delete = true;
        }else{
            $delete = false;
        }
        
        $user = DBUser::where('id', $id)->update(['expire' => date("Y/m/d H:i:s", strtotime($request->time)), 'delete_after_expired' => $delete]);

        return back()->with('success', 'Expire Date & Time added.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
