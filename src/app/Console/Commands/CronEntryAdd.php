<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\CronEntry;
use App\Models\User;

class CronEntryAdd extends Command {

  
  protected $signature = <<<EOS
              schedule:cron_add
                  {cron : cron expression ex. "* */2 * * *"}
                  {name : job name }
                  {shell_body : executable string  }
                  {?--shell= : [bash|php]. default bash}
                  {?--user= : sudo as user }
                  {?--comment= : comment strings }
                  {?--cwd= : Working Directory }
                  {?--wait_before=30 : To invoke many process at same time }
              EOS;
  protected $description = 'Add CronEntry.';
  
  public function handle () {
    $args = $this->arguments();
    
    $args = collect( $args )->only( explode( ' ', 'name shell_body cron ' ) );
    $opts = collect( $this->options() )->reject( null );
    $args = $args->merge( $opts );
    
    $args['command'] = $args['shell_body'];
    unset( $args['shell_body'] );
    $args['cron_date'] = $args['cron'];
    unset( $args['cron'] );
    $args['random_wait'] = $args['wait_before'];
    unset( $args['wait_before'] );
    $args = $args->toArray();
    $id = $this->add_cron( $args );
    $this->comment( "A schedule created, artisan schedule:cron_show ${id}\n" );
    $this->call( "schedule:cron_show", ['id' => $id] );
    return 0;
  }
  
  protected function add_cron ( $data ) {
    
    $cron = new CronEntry( $data );
    $cron->owner()->associate( User::find( 1 ) );
    $cron->save();
    return $cron->id;
  }
  
}
