<?php

namespace Tests\Feature\Model;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\CronEntry;
use Database\Seeders\UserSeeder;
use App\Models\User;
use App\Services\SyntaxCheck\Exceptions\ShellCommandNotFoundException;

class CommandExistsDetectTest extends TestCase {
  use RefreshDatabase;
  
  public function test_command_exists_error_detected () {
    $this->seed( UserSeeder::class );
    $user = User::find( 1 );
    
    $entry = new CronEntry();
    $entry->shell = 'php7.2xxx';
    $entry->command = 'whoai';
    $entry->name = __METHOD__;
    $entry->cron_date = '* * * * *';
    $entry->owner()->associate( $user );
    
    $this->expectException( ShellCommandNotFoundException::class );
    $entry->save();
  }
}
