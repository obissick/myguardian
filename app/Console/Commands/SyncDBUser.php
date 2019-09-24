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
        $db_servers = DBServer::all();

        foreach($db_servers as $db_server) {
            $dbusers = DBUser::where('server_id', $db_server->id);
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
                // display a message if connected to database successfully
                if($results){
                    foreach ($results as $result) {
                        if($dbusers){
                            /*foreach ($dbusers as $dbuser) {
                                
                                if($dbuser->user.'@'.$dbuser->host == $result->User.'@'.$results->Host){
                            
                                } 
                                else{
                                    $new = new DBUser;
                                    $new->server_id = $db_server->id;
                                    $new->user = $result->User;
                                    $new->host = $result->Host;
                                    $new->expire = null;
                                    $new->save();
                                }
                            }
                        }
                        else{*/
                            $new = new DBUser;
                            $new->server_id = $db_server->id;
                            $new->user = $result->User;
                            $new->host = $result->Host;
                            $new->expire = null;
                            $new->save();
                        }
                    } 
                }
            }catch (Exception $e){
                // report error message
                echo $e->getMessage();
            }
        }
    }
}
