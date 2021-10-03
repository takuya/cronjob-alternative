<?php

namespace App\Console\Commands;

use App\Jobs\CronJob;
use Illuminate\Console\Command;
use App\Models\CronEntry;
use App\Console\CronProcess\CronProcess;
use App\Services\ProcessExec\ProcessExecutor;
use App\Services\ProcessExec\ProcessObserver;
use App\Console\CronProcess\CronProcessExecutor;
use App\Services\ProcessExec\Events\ProcessEvents\ProcessSucceed;
use App\Services\ProcessExec\Events\ProcessEvents\ProcessEvent;
use App\Services\ProcessExec\Events\ProcessEvents\ProcessFinished;

class CronEntryRun extends Command {
  protected $signature =<<<'EOS'
    schedule:cron_run {id}
    {--F|front : Run in front, default forked background}';
    {--Q|queue : Pass CronJob to Queue}';
    EOS;

  
  protected $description = 'Run CronEntry.';
  
  public function handle () {
    $this->run_cron( $this->argument( 'id' ) );
    return 0;
  }
  
  protected function passCronJobToQueue($id, $connection='database',$name='default'){
    $entry = CronEntry::findOrFail( $id );
    $job = new CronJob($entry);
    CronJob::dispatch($entry)
      ->onConnection($connection)
      ->onQueue($name)
      ;
  }
  
  protected function run_cron_default($id){
    $con = 'sync';
    $this->passCronJobToQueue($id,$con);
  }
  protected function run_cron_queue($id, $con){
    $this->passCronJobToQueue($id,$con);
  }
  protected function run_cron_front($id){
    $entry = CronEntry::findOrFail( $id );
    
    $executor = (new CronProcessExecutor($entry ))->getExecutor();
    $observer = new ProcessObserver();
    $observer->addEventListener(ProcessFinished::class, function(ProcessEvent $event){
      $proc = $event->getExecutor()->getProcess();
      file_put_contents("php://stdout",$proc->getOutput());
      file_put_contents("php://stderr",$proc->getErrorOutput());
    });
    $executor->addObserver($observer);
    $executor->start();
  }
  protected function run_cron ( int $id ) {
    $front = $this->option('front');
    $queue = $this->option('queue');
    
    if($front){
      $this->run_cron_front($id);
    }
    else if ($queue){
      $this->run_cron_queue($id,'database');
    }else{
      $this->run_cron_default($id);
    }
    
  }
}
