<?php

namespace ProcessExec;

use PHPUnit\Framework\TestCase;
use App\Services\ProcessExec\ExecArgStruct;
use App\Services\ProcessExec\ProcessObserver;
use App\Services\ProcessExec\Events\ProcessEvents\ProcessEvent;
use App\Services\ProcessExec\Events\ProcessEvents\ProcessCanceled;
use App\Services\ProcessExec\ForkedExecutor;


class SignalCatchingTest extends TestCase {
  
  public function signalList(){
    return [
      'signal Interruption' =>[SIGINT],
      'signal Abort' =>[SIGABRT],
      'signal Term' =>[SIGTERM],
      'signal HUP' =>[SIGHUP],
      'signal Quit' =>[SIGQUIT],
      
    ];
  }
  
  /**
   * @dataProvider signalList
   * @param $signal
   *
   */
  public function test_forked_process_stop_running_by_signal($term_signal){
      //
      $arg = new ExecArgStruct();
      $arg->setCmd( ['php'] );
      $src = <<<'EOS'
      <?php sleep(3000);
    EOS;
      $arg->setInput( $src );
      $executor = new ForkedExecutor( $arg );
    
      $observer = new ProcessObserver();
      $executor->addObserver( $observer );
    
      // フォークするので変数をセマフォからもらう。
      $key_save_canceled = rand( 0, PHP_INT_MAX );
      $key_save_finished = rand( 0, PHP_INT_MAX );
    /**
     *  MacOS kern.sysv.shmseg=8 is default , it will be too many files or no space left on device error.
     *  # increase shared memory segments
     *  $ sudo vim   /etc/sysctl.conf
     *  kern.sysv.shmseg=32
     *  $ sudo reboot
     *  $ sysctl -a | grep kern.sysv.shmseg
     *  kern.sysv.shmseg: 32
     *  # view current usage of shared memory
     *  $ ipcs -m
     *  # clean up shared memory
     *  $ ipcs -m  | \grep '^m' |  awk '{print $2}' | xargs -I@ ipcrm -m @
     */
      $shm_canceled = shm_attach( $key_save_canceled, 100 );
      $shm_finished = shm_attach( $key_save_finished, 100 );
      shm_put_var( $shm_canceled, $key_save_canceled, false );
      shm_put_var( $shm_finished, $key_save_finished, false );
    
      //
      $observer->addEventListener( ProcessCanceled::class,
        function( ProcessEvent $event ) use ( $shm_canceled, $key_save_canceled ) {
          shm_put_var( $shm_canceled, $key_save_canceled, true );
        } );
      $observer->addEventListener( ProcessCanceled::class,
        function( ProcessEvent $event ) use ( $shm_finished, $key_save_finished ) {
          shm_put_var( $shm_finished, $key_save_finished, true );
        } );
    
      //
      $pid = $executor->start();
      usleep( 1000 * 80 );//ensure process start
      $pid = $executor->getSubProcessId();
    
      //
      posix_kill( $pid, $term_signal );
      usleep( 1000 * 80 );//ensure kill
    
    
      // フォーク先からデータもらう。
      $canceled_called = shm_get_var( $shm_canceled, $key_save_canceled );
      $finished_called = shm_get_var( $shm_finished, $key_save_finished );
      shm_remove($shm_canceled);
      shm_remove($shm_finished);
      //
      $this->assertTrue( $canceled_called );
      $this->assertTrue( $finished_called );
  }
  
}
