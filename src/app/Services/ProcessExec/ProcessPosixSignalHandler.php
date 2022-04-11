<?php

namespace App\Services\ProcessExec;

trait ProcessPosixSignalHandler {
  
  protected function posix_ensure_signal_attach () {
    if ( function_exists( 'pcntl_async_signals' ) ) {
      // これ入れないとsignalまで制御が来ないことがある。
      // see https://www.slideshare.net/do_aki/20171008-signal-onphp
      pcntl_async_signals( true );
    }
  }
}
