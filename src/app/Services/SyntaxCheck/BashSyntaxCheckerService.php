<?php

namespace App\Services\SyntaxCheck;


use App\Services\SyntaxCheck\Exceptions\BashInvalidSyntaxError;

class BashSyntaxCheckerService extends SyntaxCheckService {
  
  protected $cmd = ['bash', '-n'];
  protected $exception_class = BashInvalidSyntaxError::class;
  
  
}