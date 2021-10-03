<?php

namespace SchedulingRun;

use Tests\Artisan\ArtisanCallTestCase;
use App\Models\CronEntry;
use App\Models\CronLog;
use App\Models\User;
use App\Console\CronProcess\CronProcessExecutor;

class ChangeEnvVariableTest extends ArtisanCallTestCase {
  
  
  public function test_schedule_run_with_env_variables () {
    $user = User::find( 1 );
    
    
    $name = __METHOD__.time();
    $entry = new CronEntry();
    $entry->command = 'echo -n $MY_NAME';
    $entry->name = $name;
    $entry->env = ['MY_NAME' => ($my_name = 'takuya')];
    $entry->cron_date = '* * * * *';
    $entry->owner()->associate( $user );
    $entry->save();
    $entry->refresh();
    //
    $job = new CronProcessExecutor($entry );
    $job->handle();
    $job_id = $job->getScheduleId();
    $log = CronLog::where( 'schedule_id', $job_id )->firstOrFail();
    $this->assertEquals( $my_name, trim( $log->stdout ) );
  }
  public function test_schedule_run_php_with_env_variables () {
    $user = User::find( 1 );
    $name = __METHOD__.time();
    $entry = new CronEntry();
    $entry->command = '<?php echo getenv("MY_NAME");';
    $entry->shell = 'php';
    $entry->name = $name;
    $entry->env = ['MY_NAME' => $my_name = 'takuya'];
    $entry->cron_date = '* * * * *';
    $entry->owner()->associate( $user );
    $entry->save();
    $entry->refresh();
    //
    $job = new CronProcessExecutor($entry );
    $job->handle();
    $job_id = $job->getScheduleId();
    $log = CronLog::where( 'schedule_id', $job_id )->firstOrFail();
    $this->assertEquals( $my_name, trim( $log->stdout ) );
  }
  public function test_schedule_run_php_with_env_variables_with_sudo () {
    $user = User::find( 1 );
    $name = __METHOD__.time();
    $entry = new CronEntry();
    $entry->command = '<?php echo getenv("MY_NAME");';
    $entry->shell = 'php';
    $entry->name = $name;
    $entry->user = exec('whoami');
    $entry->env = ['MY_NAME' => $my_name = 'takuya'];
    $entry->cron_date = '* * * * *';
    $entry->owner()->associate( $user );
    $entry->save();
    $entry->refresh();
    //
    $job = new CronProcessExecutor($entry );
    $job->handle();
    $job_id = $job->getScheduleId();
    $log = CronLog::where( 'schedule_id', $job_id )->firstOrFail();
    $this->assertEquals( $my_name, trim( $log->stdout ) );
  }
}
