<?php

namespace App\Jobs;

use App\Models\CronEntry;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use App\Console\CronProcess\CronProcess;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class CronJob implements ShouldQueue {
  
  use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
  
  /**
   * @var \App\Models\CronEntry
   */
  protected $entry;
  
  /**
   * Create a new job instance.
   * @return void
   */
  public function __construct( CronEntry $entry ) {
    $this->entry = $entry;
  }
  
  /**
   * Execute the job.
   * @return void
   */
  public function handle() {
    $executor = app()->make(CronProcess::class, ['job' => $this->entry]);
    $pid = $executor->start();
    if( $pid ) {
      echo "Started '{$this->entry->name}'".( $pid ? " at process {$pid}" : '' ).".\n";
    }
  }
}
