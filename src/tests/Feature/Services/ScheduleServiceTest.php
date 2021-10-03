<?php

namespace Tests\Feature\Services;
use Tests\TestCase;
use App\Services\ScheduleService;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use App\Models\CronEntry;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use Database\Seeders\UserSeeder;
use Database\Seeders\CronEntrySeeder;


class ScheduleServiceTest extends TestCase{
  
  use RefreshDatabase;
  public function test_get_scheduled_jobs(){
    $this->seed( UserSeeder::class );
  
    $user = User::find( 1 );
  
    // #1
    $entry = new CronEntry();
    $entry->shell = 'php';
    $entry->command = 'aaa';
    $entry->name = 'first';
    $entry->cron_date = '11 11 * * *';
    $entry->owner()->associate( $user );
    $entry->save();
    // #2
    $entry = new CronEntry();
    $entry->shell = 'php';
    $entry->command = 'bbb';
    $entry->name = 'second';
    $entry->cron_date = '12 11 * * *';
    $entry->owner()->associate( $user );
    $entry->save();
    // #3
    $entry = new CronEntry();
    $entry->shell = 'php';
    $entry->command = 'bbb';
    $entry->name = 'second';
    $entry->cron_date = '9 11  * * *';
    $entry->owner()->associate( $user );
    $entry->save();
    // default
    $ret = ScheduleService::getSchedulesAsArray();
    $first = $ret[0];
    $this->assertEquals(1,$first['cron_entry_id']);
    // asc
    $ret = ScheduleService::getSchedulesAsArray('asc');
    $first = $ret[0];
    $this->assertEquals(3,$first['cron_entry_id']);
    // desc
    $ret = ScheduleService::getSchedulesAsArray('desc');
    $first = $ret[0];
    $this->assertEquals(2,$first['cron_entry_id']);
  }
}