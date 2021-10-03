<?php

namespace App\Console\CronProcess;

use App\Services\ProcessExec\ExecArgStruct;
use App\Models\CronEntry;

class CronEntryProcessAdaptor extends ExecArgStruct {
  
  
  public function __construct ( CronEntry $entry ) {
    $this->setCwd( $entry->cwd );
    $this->setInput( $entry->command );
    $this->setEnv( $entry->env );
    $this->setCmd( $this->getCronEntryShell( $entry ) );
  }
  
  protected static function getCronEntryShell ( $entry ): array {
    // add sudo
    $shell = collect( [] )
      ->merge( $entry->user ? ['sudo','-E', '-u', $entry->user] : [] )
      ->merge( [$entry->shell] )
      ->toArray();
    return $shell;
  }
  
}