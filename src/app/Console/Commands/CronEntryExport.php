<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\CronEntry;

class CronEntryExport extends Command {
  protected $signature = 'schedule:cron_export {id? : ID for cron entry }';
  protected $description = 'export cron job ';
  
  protected function export_all () {
    $entries = CronEntry::all();
    
    $opts = JSON_PRETTY_PRINT
      | JSON_UNESCAPED_LINE_TERMINATORS
      | JSON_UNESCAPED_SLASHES
      | JSON_UNESCAPED_UNICODE;
    
    $str = $entries->toJson( $opts );
    $this->line($str);
  }
  
  protected function export ( $id ) {
    $entry = CronEntry::findOrFail( $id );
    $opts = JSON_PRETTY_PRINT
      | JSON_UNESCAPED_LINE_TERMINATORS
      | JSON_UNESCAPED_SLASHES
      | JSON_UNESCAPED_UNICODE;
    $str = $entry->toJson( $opts );
    $this->line($str);
  }
  
  public function handle () {
    if ( $this->argument( 'id' ) ) {
      $this->export( $this->argument( 'id' ) );
    } else {
      $this->export_all();
    }
  }
}
