<?php

namespace Scheduling;

use Tests\Artisan\ArtisanCallTestCase;
use App\Models\CronEntry;
use App\Models\User;

class UpdateScheduleModelTest extends ArtisanCallTestCase {
  
  
  public function test_schedule_update_one_model_list () {
    $user = User::find( 1 );
    
    
    $name = __METHOD__.time();
    $entry = new CronEntry();
    $entry->command = 'whoami';
    $entry->name = $name;
    $entry->cron_date = '* * * * *';
    $entry->owner()->associate( $user );
    $entry->save();
    
    $last_id = $entry->id;
    
    $ret = $this->artisan_call( 'schedule:list' );
    $this->assertStringContainsString( $name, $ret );
    
    $entry->cron_date = '@daily';
    $entry->save();
    
    $this->assertNotEmpty( CronEntry::find( $last_id ) );
    
    $ret = $this->artisan_call( 'schedule:list' );
    $this->assertStringContainsString( $name, $ret );
    
    $ret = $this->artisan_call( 'schedule:list' );
    $this->assertStringContainsString( "@daily", $ret );
  }
}
