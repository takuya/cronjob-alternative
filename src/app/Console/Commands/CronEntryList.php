<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\CronEntry;

class CronEntryList extends Command {
  protected $signature = 'schedule:cron_list';
  
  protected $description = 'List CronEntry.';
  
  public function handle () {
    $this->list_cron();
    return 0;
  }
  
  protected function list_cron () {
    $headers = ['id', 'name', 'cron_date', 'shell', 'command', 'enabled',];
    $list = CronEntry::all( $headers );
    $list->map( function( $e ) {
      $e['enabled'] = $e['enabled'] ? 'true' : 'false';
      return $e;
    } );
    $this->table( $headers, $list );
  }
}
