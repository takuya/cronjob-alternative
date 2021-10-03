<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use Symfony\Component\Process\Process;

class SymfonyProcessWaitTest extends TestCase {
  
  public function test_symfony_process_wait () {
    $body = <<<'EOS'
      <?php
          for ( $i=1;$i<100;$i++){
            echo $i.PHP_EOL;
            echo str_repeat('a',1024*10).PHP_EOL;
            usleep(1000* 0.01);
          }
      EOS;
    
    $proc = new Process( ['php'] );
    $proc->setTty( false );
    $proc->setPty( false );
    $proc->setTimeout( null );
    $proc->setIdleTimeout( null );
    $proc->setInput( $body );
    
    $proc->start();
    $strout = 'a';
    $proc->wait( function( $type, $buffer ) use ( $proc, &$strout ) {
      if ( Process::ERR === $type ) {
        echo 'ERR > '.$buffer;
      } else {
        $strout = $proc->getOutput();
        usleep( 1000 * 10 );
      }
    } );
    
    $ret = $proc->getOutput();
    $this->assertEquals( $ret, $strout );
  }
}
