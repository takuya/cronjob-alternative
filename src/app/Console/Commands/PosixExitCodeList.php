<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class PosixExitCodeList extends Command {
  protected $signature = 'schedule:cron:posix_code:list';
  
  protected $description = 'Show posix exit_status_code list';
  
  /**
   * Execute the console command.
   *
   * @return int
   */
  public function handle () {
    $ret = [];
    foreach ( range( 0, 255 ) as $code ) {
      $ret[] = [
        'code' => $code,
        'description' => pcntl_strerror( $code ),
      ];
    }
    $this->table( ['exit code', 'description'], $ret );
    return 0;
  }
}
