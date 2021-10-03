<?php

namespace Scheduling;

use Tests\Artisan\ArtisanCallTestCase;
use App\Models\CronEntry;
use App\Models\CronLog;
use App\Models\User;

class RunScheduleModelTest extends ArtisanCallTestCase {
  
  
  public function test_schedule_run_cron_model_and_check_log () {
    $user = User::find( 1 );
    
    
    $name = __METHOD__.time();
    $entry = new CronEntry();
    $entry->command = 'echo -n Hello world;';
    $entry->name = $name;
    $entry->cron_date = '* * * * *';
    $entry->owner()->associate( $user );
    $entry->save();
    
    
    $ret = $this->artisan_call( 'schedule:run' );
    $this->assertStringContainsString( $entry->name, $ret );
    usleep( 1000 * 200 );
    
    $log = CronLog::where( 'cron_entry', 'like', "%\"id\":{$entry->id}%" )
                  ->first();
    while ( $log->exit_status_code === null ) {
      usleep( 1000 * 200 );
      $log->refresh();
    }
    
    $this->assertDatabaseHas( ( new CronLog() )->getTable(), [
      'name' => $name,
      'stdout' => "Hello world",
      'stderr' => '',
      'exit_status_code' => 0,
    ] );
  }
}
