<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Capsule\Manager as Capsule;

use App\DBServer;
use App\DBUser;

class ExpireDBUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'dbusers:expire';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Remove permissions for expired users.';

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
        //get all servers
        $db_servers = DBServer::all();
        //find expired users for each server.
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
                //find users where expire date and time less that current.
                $dbusers = DBUser::where([['server_id', $db_server->id],['expire', '<=', date("Y-m-d H:i:s")], ['expire', '!=', null]])->get();
                if(!$dbusers->isEmpty()){
                    foreach ($dbusers as $dbuser) {
                        echo "User: ".$dbuser->user."@".$dbuser->host." Expired, removing access.\n";
                        $results = Capsule::raw("REVOKE ALL PRIVILEGES, GRANT OPTION FROM '".$dbuser->user."'@'".$dbuser->host."'");
                        DBUser::where('id', $dbuser->id)->update(['expired' => true, 'expire' => null]);
                        if($dbuser->delete_after_expired){
                            $results = Capsule::raw("DROP USER '".$dbuser->user."'@'".$dbuser->host."'");
                        }
                    } 
                }
            }catch (Exception $e){
                // report error message
                echo $e->getMessage();
            }
            Capsule::disconnect();
        }
    }
}
