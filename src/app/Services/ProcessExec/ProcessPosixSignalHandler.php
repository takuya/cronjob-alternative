<?php

namespace App\Services\ProcessExec;

trait ProcessPosixSignalHandler {
  
  protected function posix_ensure_signal_attach () {
    if ( function_exists( 'pcntl_async_signals' ) ) {
      pcntl_async_signals( true );// これ入れないとsignalまで制御が来ないことがある。
    }
  }
}