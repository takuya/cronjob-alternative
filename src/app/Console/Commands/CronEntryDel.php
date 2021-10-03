<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\CronEntry;

class CronEntryDel extends Command {
  protected $signature = 'schedule:cron_del {id}';
  
  protected $description = 'Remove CronEntry {ID}.';
  
  protected $question_string = 'This CronEntry about to delete. Are you sure?';
  
  public function handle () {
    $id = $this->argument( 'id' );
    
    $this->comment( "This CronEntry about to delete.\n" );
    $this->call( "schedule:cron_show", ['id' => $id] );
    
    if ( $this->confirm( $this->question_string, false ) ) {
      $this->del_cron( $this->argument( 'id' ) );
      $this->line( 'Deleted!.' );
    }
    
    return 0;
  }
  
  protected function del_cron ( int $id ) {
    $entry = CronEntry::findOrFail( $id );
    $entry->enabled = false;
    $entry->delete();
  }
  
}
