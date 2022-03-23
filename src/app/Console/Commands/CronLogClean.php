<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\CronEntry;
use App\Models\Repositories\CronLogRepository;
use App\Models\CronLog;

class CronLogClean extends Command {
  protected $signature = 'schedule:cron_log:clean {id=all} {--keep=20}';
  
  protected $description = "clean log of CronJob.".
  " --keep=10 means keep last 10 logs, and remove before last 11'th logs";
  
  public function handle () {
    $id = $this->argument( 'id' );
    $keep = $this->option( 'keep' );
    $this->list_cron_log();
    if ( $this->confirm( "Truncate logs size to {$keep}? ", false ) ) {
      $this->remove_cron_log($id,$keep);
    }
    if ( $this->confirm( "remove log of removed CronEntry? ", false ) ) {
      $this->logs_without_cron_entry();
    }
    $this->line("Current log size.");
    $this->list_cron_log();
    return 0;
  }
  
  protected function logs_without_cron_entry () {
    $list = CronLogRepository::OrphanLog();
    $list->each(function(CronLog $e){
      $e->delete();
    });
    $this->line("Orphan log: {$list->count()} record removed.");
    return $list;
  }
  
  protected function remove_cron_log ($id, $keep) {
    if ( $id == "all" ) {
      foreach ( CronEntry::all() as $e ) {
        $this->remove_cron_log_by_id( $e->id, $keep );
      }
    } else {
      $e = CronEntry::findOrFail( $id );
      $this->remove_cron_log_by_id( $e->id, $keep );
    }
  }
  
  protected function remove_cron_log_by_id ( $id, $keep ) {
    $e = CronEntry::findOrFail( $id );
    $size = $e->logs()->count();
    $e->logs()->offset( $keep )->limit( $size )->delete();
    $this->line("CronEntry({$id}) log truncated.");
  }
  
  protected function list_cron_log () {
    $headers = ['id', 'name'];
    $list = CronEntry::all( $headers );
    $list = $list->map( function( CronEntry $e ) {
      $e->logs = $e->logs()->count();
      return $e->toArray();
    } );
    $this->table( array_merge( $headers, ['log'] ), $list->toArray() );
  }
}
