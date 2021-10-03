<?php

namespace App\Http\Controllers;

use Symfony\Component\Process\Process;

class JournaldLogViewController extends Controller {
  
  public function show() {
    
    
    $service_name = 'cron-laravel';
    $from = strftime("%Y-%m-%d %H:%M:%S", strtotime('-2 days'));
    $cmd = "journalctl -r -u '$service_name' --since '$from'";
    if( preg_match('/dev|local/', config('app.env')) ) {
      $cmd = "ssh s0 -- \"$cmd\"";
    }
    $proc = Process::fromShellCommandline($cmd);
    $proc->run();
    $out = $proc->getOutput();
    
    return view('admin.journald', ['cmd' => $cmd, 'out' => $out]);
  }
}
