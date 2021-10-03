<?php

namespace App\Console\CronProcess;

use App\Services\ProcessExec\ForkedExecutor;

class ForkedCronProcessExecutor extends CronProcessExecutor {
  
  
  public function getSubProcessId (): int {
    return $this->executor->getSubProcessId();
  }
  
  protected function prepareExecutor () {
    $exec_struct = new CronEntryProcessAdaptor( $this->entry );
    $this->executor = new ForkedExecutor( $exec_struct );
  }
  
  
}