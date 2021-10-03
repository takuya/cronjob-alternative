<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\CronEntry;

class CronEntryDisable extends Command {
  protected $signature = 'schedule:cron_disable {id}';
  
  protected $description = 'Disable {ID}, skip.';
  
  public function handle () {
    $this->stop_cron( $this->argument( 'id' ) );
    return 0;
  }
  
  protected function stop_cron ( int $id ) {
    $entry = CronEntry::findOrFail( $id );
    $entry->enabled = false;
    $entry->save();
  }
}
