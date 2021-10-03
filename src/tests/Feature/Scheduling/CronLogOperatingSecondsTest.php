<?php

namespace Scheduling;

use App\Models\CronLog;
use App\Models\CronEntry;
use Tests\Artisan\ArtisanCallTestCase;
use App\Models\User;
use App\Console\CronProcess\CronProcessExecutor;

class CronLogOperatingSecondsTest extends ArtisanCallTestCase {
  
  
  public function test_calculate_execution_seconds () {
    $user = User::find( 1 );
    
    $name = dechex( crc32( __METHOD__.time() ) );
    $entry = CronEntry::create( [
      'name' => $name,
      'command' => 'echo end;',
      'cron_date' => '* * * * *',
      'owner_id' => $user->id,
    ] )->refresh();
    
    
    $executor = new CronProcessExecutor($entry );
    $executor->start();
    usleep( 1000 * 200 );
    
    
    $log = CronLog::where( 'schedule_id', $executor->getScheduleId() )->firstOrFail();
    $this->assertIsFloat( $log->operating_time );
    $this->assertIsInt( $log->exit_status_code );
    $this->assertStringContainsString( 'end', $log->stdout );
  }
}
