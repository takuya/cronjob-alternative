<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Models\Repositories\CronEntryRepository;

class Kernel extends ConsoleKernel {
  /**
   * The Artisan commands provided by your application.
   *
   * @var array
   */
  protected $commands = [
    //
  ];
  
  /**
   * Define the application's command schedule.
   *
   * @param Schedule $schedule
   * @return void
   */
  protected function schedule ( Schedule $schedule ) {
    CronEntryRepository::scheduling_all_entries( $schedule );
  }
  
  /**
   * Register the commands for the application.
   *
   * @return void
   */
  protected function commands () {
    $this->load( __DIR__.'/Commands' );
    
    require base_path( 'routes/console.php' );
  }
}
