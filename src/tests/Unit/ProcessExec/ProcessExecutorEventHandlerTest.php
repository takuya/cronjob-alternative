<?php

namespace ProcessExec;

use PHPUnit\Framework\TestCase;
use App\Services\ProcessExec\ExecArgStruct;
use App\Services\ProcessExec\ProcessExecutor;
use App\Services\ProcessExec\ProcessObserver;
use App\Services\ProcessExec\Events\ProcessEvents\ProcessEvent;
use App\Services\ProcessExec\Events\ProcessEvents\ProcessStarted;
use App\Services\ProcessExec\Events\ProcessEvents\ProcessErrorOccurred;
use App\Services\ProcessExec\Events\ProcessEvents\ProcessRunning;
use App\Services\ProcessExec\Events\ProcessEvents\ProcessReady;
use App\Services\ProcessExec\Events\ProcessEvents\ProcessSucceed;
use App\Services\ProcessExec\Events\ProcessEvents\ProcessFinished;
use Exception;

class ProcessExecutorEventHandlerTest extends TestCase {
  
  public function test_process_exec_running_event_observed () {
    $arg = new ExecArgStruct();
    $arg->setCmd( ['php'] );
    $src = <<<'EOS'
      <?php
        for($i=0;$i<5;$i++){
          echo 'Hello World'.PHP_EOL;
          usleep(1000*120);
        }
      EOS;
    $arg->setInput( $src );
    $executor = new ProcessExecutor( $arg );
    $observer = new ProcessObserver();
    $executor->watch_interval = 0.1;
    
    $running_called_cnt = 0;
    $observer->addEventListener( ProcessRunning::class,
      function( ProcessEvent $event ) use ( &$running_called_cnt ) {
        $running_called_cnt++;
      } );
    $executor->addObserver( $observer );
    $executor->start();
    $this->assertEquals( 5, $running_called_cnt );
  }
  
  public function test_process_exec_error_observed () {
    $id = uniqid( __METHOD__ );
    $arg = new ExecArgStruct();
    $arg->setCmd( ['php'] );
    $src = <<<'EOS'
      <?php
        for($i=0;$i<10;$i++){
          echo 'Hello World'.PHP_EOL;
          usleep(1000*10);
        }
      EOS;
    $arg->setInput( $src );
    $executor = new ProcessExecutor( $arg );
    $executor->watch_interval = 0.1;
    $observer = new ProcessObserver();
    $is_error_called = false;
    $observer->addEventListener( ProcessErrorOccurred::class,
      function( ProcessEvent $event ) use ( &$is_error_called, $id ) {
        $ex = $event->getExecutor()->getLastException();
        $this->assertEquals( $id, $ex->getMessage() );
        $proc = $event->getExecutor()->getProcess();
        $proc->signal( SIGHUP );
        $proc->wait();
        $this->assertEquals( 'terminated', $proc->getStatus() );
        $is_error_called = true;
      } );
    $observer->addEventListener( ProcessRunning::class, function() use ( $id ) {
      throw new Exception( $id );
    } );
    $executor->addObserver( $observer );
    $executor->start();
    
    $this->assertTrue( $is_error_called );
  }
  
  public function test_process_exec_with_observer () {
    $arg = new ExecArgStruct();
    $arg->setCmd( ['php'] );
    $src = <<<'EOS'
      <?php
        for($i=0;$i<10;$i++){
          echo 'Hello World'.PHP_EOL;
          usleep(1000*1);
        }
      EOS;
    $arg->setInput( $src );
    
    
    $executor = new ProcessExecutor( $arg );
    $executor->watch_interval = 0.01;
    $observer = new ProcessObserver();
    
    $is_ready_called = false;
    $observer->addEventListener( ProcessReady::class,
      function( ProcessEvent $event ) use ( &$is_ready_called, $src ) {
        $is_ready_called = true;
        $proc = $event->getExecutor()->getProcess();
        $this->assertEquals( $src, $proc->getInput() );
      } );
    
    $is_start_called = false;
    $observer->addEventListener( ProcessStarted::class,
      function( ProcessEvent $event ) use ( &$is_start_called ) {
        $is_start_called = true;
        $proc = $event->getExecutor()->getProcess();
        $this->assertEquals( 'started', $proc->getStatus() );
        $this->assertNull( $proc->getExitCode() );
        $this->assertIsInt( $proc->getPid() );
        $this->assertEmpty( "", $proc->getOutput() );
      } );
    
    $is_running_called = false;
    $observer->addEventListener( ProcessRunning::class,
      function( ProcessEvent $event ) use ( &$is_running_called ) {
        $is_running_called = true;
        $proc = $event->getExecutor()->getProcess();
        $this->assertEquals( 'started', $proc->getStatus() );
        $this->assertNull( $proc->getExitCode() );
        $this->assertIsInt( $proc->getPid() );
      } );
    
    $is_success_called = false;
    $observer->addEventListener( ProcessSucceed::class,
      function( ProcessEvent $event ) use ( &$is_success_called ) {
        $is_success_called = true;
        $proc = $event->getExecutor()->getProcess();
        $out = $proc->getOutput();
        $this->assertTrue( $proc->isSuccessful() );
        $this->assertEquals( 10, substr_count( $out, 'Hello World' ) );
      } );
    
    $is_finish_called = false;
    $observer->addEventListener( ProcessFinished::class,
      function( ProcessEvent $event ) use ( &$is_finish_called ) {
        $is_finish_called = true;
        $proc = $event->getExecutor()->getProcess();
        $out = $proc->getOutput();
        $this->assertEquals( 10, substr_count( $out, 'Hello World' ) );
      } );
    
    //
    $executor->addObserver( $observer );
    $executor->start();
    
    
    //
    $this->assertTrue( $is_ready_called );
    $this->assertTrue( $is_start_called );
    $this->assertTrue( $is_running_called );
    $this->assertTrue( $is_success_called );
    $this->assertTrue( $is_finish_called );
  }
}