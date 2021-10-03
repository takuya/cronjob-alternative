<?php

namespace App\Http\Controllers;

use Symfony\Component\Process\Process;

class SystemdServiceController extends Controller {
  
  public function show() {
    
    
    $service_name = 'cron-laravel.service';
    $cmd = "systemctl status $service_name";
    if( preg_match('/dev|local/', config('app.env')) ) {
      $cmd = "ssh s0 -- \"$cmd\"";
    }
    $proc = Process::fromShellCommandline($cmd);
    $proc->run();
    $out = $proc->getOutput();
    
    return view('admin.journald', ['cmd' => $cmd, 'out' => $out]);
  }
}
