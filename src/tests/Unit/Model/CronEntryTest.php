<?php

namespace Model;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\CronEntry;
use App\Exceptions\InvalidCronExpression;
use App\Models\User;
use Database\Seeders\UserSeeder;

class CronEntryTest extends TestCase {
  
  use RefreshDatabase;
  
  
  public function test_create_cron_entry () {
    //
    $this->seed( UserSeeder::class );
    $user = User::find( 1 );
    
    $entry = new CronEntry();
    $entry->command = 'whoami';
    $entry->name = __METHOD__;
    $entry->cron_date = '* * * * *';
    $entry->owner()->associate( $user );
    $entry->save();
    
    $this->assertDatabaseHas( $entry->getTable(), [
      'name' => __METHOD__,
    ] );
  }
  
  public function test_invalid_cron_expression () {
    $entry = new CronEntry();
    $this->expectException( InvalidCronExpression::class );
    $entry->cron_date = '* * * * * *';
  }
  
  public function test_get_name_default () {
    $this->seed( UserSeeder::class );
    $user = User::find( 1 );
    $entry = new CronEntry();
    $entry->command = 'whoami';
    $entry->cron_date = '* * * * *';
    $entry->owner()->associate( $user );
    ///
    $entry->save();
    $this->assertDatabaseHas( $entry->getTable(), [
      'name' => get_classname_base( CronEntry::class ),
    ] );
  }
}
