<?php

namespace Tests\Artisan\ScheduleCron;

use Tests\Artisan\ArtisanCallTestCase;
use App\Console\Commands\CronEntryAdd;
use App\Console\Commands\CronEntryList;
use RuntimeException;

class CronCmdAddTest extends ArtisanCallTestCase {
  
  public function test_schedule_cron_add_throws_exception () {
    $name = $this->getCommandName( CronEntryAdd::class );
    $this->expectException( RuntimeException::class );
    $this->artisan_call( $name );
  }
  
  public function test_schedule_cron_entry_add () {
    $name = __METHOD__.time();
    
    
    $command = $this->getCommandName( CronEntryAdd::class );
    $this->artisan_call( $command, [
      "name" => $name, "shell_body" => 'echo Hello world;', "cron" => '* * * * *',
    ] );
    
    $ret = $this->artisan_call( 'schedule:list' );
    $this->assertStringContainsString( $name, $ret );
    
    $ret = $this->artisan_call( $this->getCommandName( CronEntryList::class ) );
    $this->assertStringContainsString( $name, $ret );
  }
}
