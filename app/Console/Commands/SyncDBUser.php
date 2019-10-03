<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Capsule\Manager as Capsule;

use App\DBServer;
use App\DBUser;

class SyncDBUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'dbusers:sync';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync users from database servers.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->processAdd();
        $this->processDelete();
    }

    public function processAdd(){
        $db_servers = DBServer::all();

        foreach($db_servers as $db_server) {
            //$dbusers = DBUser::where('server_id', $db_server->id)->get();
            echo "Getting users from server: ".$db_server->name."\n";
            $capsule = new Capsule;
            $capsule->addConnection([
                'driver'    => 'mysql',
                'host'      => $db_server->ip,
                'database'  => 'mysql',
                'username'  => $db_server->username,
                'password'  => Crypt::decrypt($db_server->password),
                'charset'   => 'utf8',
                'collation' => 'utf8_unicode_ci',
                'prefix'    => '',
            ]);
            $capsule->setAsGlobal();
            try{
                $results = Capsule::select('select * from user');
                if($results){
                    foreach ($results as $result) {
                        $dbuser = DBUser::where([['server_id', $db_server->id], ['user', $result->User], ['host', $result->Host]])->get();
                        if($dbuser->isEmpty()){
                            echo "Adding new user: ".$result->User."@".$result->Host."\n";
                            $this->adduser($result, $db_server);
                        }
                    } 
                }
            }catch (Exception $e){
                // report error message
                echo $e->getMessage();
            }
        }
    }

    public function processDelete()
    {
        $db_servers = DBServer::all();

        foreach($db_servers as $db_server) {
            $capsule = new Capsule;
            $capsule->addConnection([
                'driver'    => 'mysql',
                'host'      => $db_server->ip,
                'database'  => 'mysql',
                'username'  => $db_server->username,
                'password'  => Crypt::decrypt($db_server->password),
                'charset'   => 'utf8',
                'collation' => 'utf8_unicode_ci',
                'prefix'    => '',
            ]);
            $capsule->setAsGlobal();
            try{
                $dbusers = DBUser::where([['server_id', $db_server->id]])->get();
                if(!$dbusers->isEmpty()){
                    foreach ($dbusers as $dbuser) {
                        $results = Capsule::select("select * from user where User='".$dbuser->user."' and Host='".$dbuser->host."'");
                        if(empty($results)){
                            echo "User doesnt exist in source, removing user from myguardian: ".$dbuser->user."\n";
                            $this->removeuser($dbuser->id);
                        }    
                    } 
                }
            }catch (Exception $e){
                // report error message
                echo $e->getMessage();
            }
        }
    }

    public function adduser($result, $db_server)
    {
        $new = new DBUser;
        $new->server_id = $db_server->id;
        $new->user = $result->User;
        $new->host = $result->Host;
        $new->expire = null;
        $new->save();
    }

    public function removeuser($id){
        DBUser::where('id', $id)->delete();
    }
}
