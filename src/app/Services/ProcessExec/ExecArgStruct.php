<?php

namespace App\Services\ProcessExec;

use Symfony\Component\Process\Process;

class ExecArgStruct {
  protected $cmd = [];
  protected $env;
  protected $cwd;
  protected $input;
  
  public function prepareProcess (): Process {
    $proc = new Process( ...$this->get_proc_args() );
    $proc->setTimeout( null );
    $proc->setIdleTimeout( null );
    $proc->setTty( false );
    $proc->setPty( false );
    return $proc;
  }
  
  protected function get_proc_args (): array {
    return [
      $this->getCmd(),
      $this->getCwd(),
      $this->getEnv(),
      $this->getInput(),
    ];
  }
  
  /**
   * @return array
   */
  public function getCmd (): array {
    return $this->cmd;
  }
  
  /**
   * @param array $cmd
   */
  public function setCmd ( array $cmd ): void {
    $this->cmd = $cmd;
  }
  
  /**
   * @return mixed
   */
  public function getCwd () {
    return $this->cwd;
  }
  
  /**
   * @param mixed $cwd
   */
  public function setCwd ( $cwd ): void {
    $this->cwd = $cwd;
  }
  
  /**
   * @return mixed
   */
  public function getEnv () {
    return $this->env;
  }
  
  /**
   * @param mixed $env
   */
  public function setEnv ( $env ): void {
    $this->env = $env;
  }
  
  /**
   * @return mixed
   */
  public function getInput () {
    return $this->input;
  }
  
  /**
   * @param mixed $input
   */
  public function setInput ( $input ): void {
    $this->input = $input;
  }
  
}