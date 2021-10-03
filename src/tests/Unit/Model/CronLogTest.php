<?php

namespace Tests\Unit\Model;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\CronLog;
use App\Models\CronEntry;
use Database\Seeders\UserSeeder;
use App\Models\User;
use Str;

class CronLogTest extends TestCase {
  
  use RefreshDatabase;
  
  public function test_create_cron_job_log () {
    $this->seed( UserSeeder::class );
    $user = User::find( 1 );
    
    $entry = new CronEntry( [
      'command' => 'whoami',
      'cron_date' => '* * * * *',
      'name' => __METHOD__.time(),
      'owner_id' => $user->id,
    ] );
    $entry->save();
    $log = new CronLog( [
      'schedule_id' => Str::random( 10 ),
      'cron_entry' => $entry,
      'stdout' => 'takuya',
      'stderr' => '',
      'exit_status_code' => 0,
    ] );
    $log->save();
    
    
    $this->assertDatabaseHas( $log->getTable(), [
      'cron_entry' => $entry->toJson(),
    ] );
  }
}
