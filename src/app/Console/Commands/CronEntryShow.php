<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\CronEntry;

class CronEntryShow extends Command {
  protected $signature = 'schedule:cron_show {id}';
  
  protected $description = 'Show CronEntry {ID}.';
  
  public function handle () {
    $this->show_cron( $this->argument( 'id' ) );
    return 0;
  }
  
  protected function show_cron ( int $id ) {
    $entry = CronEntry::findOrFail( $id );
    $entry->makeHidden( 'owner' );
    
    $arr = $entry->toArray();
    $arr['owner'] = "{$entry->owner->name}<{$entry->owner->email}>";
    
    $data = collect( $arr )->map( function( $v, $k ) {
      return [$k, $v];
    } );
    $this->table( ['key', 'value'], $data );
  }
}
