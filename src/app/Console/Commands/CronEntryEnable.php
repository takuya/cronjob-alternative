<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\CronEntry;

class CronEntryEnable extends Command {
  protected $signature = 'schedule:cron_enable {id}';
  
  protected $description = 'Enable {ID}, skip.';
  
  public function handle () {
    $this->stop_cron( $this->argument( 'id' ) );
    return 0;
  }
  
  protected function stop_cron ( int $id ) {
    $entry = CronEntry::findOrFail( $id );
    $entry->enabled = true;
    $entry->save();
  }
}
