<?php

namespace App\Console\CronProcess;

use App\Models\CronEntry;
use Symfony\Component\Process\Process;
use Exception;

interface CronProcess {
  public function __construct ( CronEntry $job );
  
  public function start ();
  
  public function __invoke ();
  
  public function getCronEntry (): CronEntry;
  
  public function getScheduleId ();
  
  public function getOperatingTime ();
  
  public function getSubProcessId ();
  
  public function getLastException (): Exception;
  
  public function getProcess (): Process;
}