<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Exceptions\ForkFailedException;

class CronWork extends Command {
  
  protected $signature   = 'cron:work';
  protected $description = 'start schedule:run,queue:work';
  
  protected $pids=[];
  
  public function start_schedule_work(){
    $pid = pcntl_fork();
    if($pid===-1){
      throw new ForkFailedException('pcntl_fork に失敗');
    }
    if ($pid===0){
      chdir( base_path() );
      pcntl_async_signals(true);
      $php = find_php_path();
      pcntl_exec($php,['artisan','schedule:work']);
      exit(0);
    }
    return $pid;
  }
  public function start_queue_work(){
    $pid = pcntl_fork();
    if($pid===-1){
      throw new ForkFailedException('pcntl_fork に失敗');
    }
    if ($pid===0){
      chdir( base_path() );
      pcntl_async_signals(true);
      $php = find_php_path();
      // sleep を 60s 以上にすると、php.iniのtimeout設定により強制終了される。
      pcntl_exec($php,['artisan','queue:work','database','--sleep=30']);
      exit(0);
    }
    return $pid;
  }
  
  public function start_workers(){
    pcntl_async_signals(true);
    $p1 = $this->start_queue_work();
    $p2 =$this->start_schedule_work();
    $this->pids = [$p1, $p2];
    pcntl_sigwaitinfo([SIGHUP, SIGINT,SIGQUIT,SIGTERM,SIGABRT]);
  }
  
  public function handle() {
    posix_setsid();
    $this->start_workers();
    return 0;
  }
}
