<?php

namespace Scheduling;

use Tests\Artisan\ArtisanCallTestCase;
use App\Models\CronEntry;
use App\Models\User;

class DelScheduleModelTest extends ArtisanCallTestCase {
  
  
  public function test_schedule_del_one_model_list () {
    //
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
    
    $entry->delete();
    
    $this->assertEmpty( CronEntry::find( $last_id ) );
    
    $ret = $this->artisan_call( 'schedule:list' );
    $this->assertStringNotContainsString( $name, $ret );
  }
}
