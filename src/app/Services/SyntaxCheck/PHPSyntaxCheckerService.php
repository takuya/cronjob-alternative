<?php


namespace App\Services\SyntaxCheck;

use App\Services\SyntaxCheck\Exceptions\PHPInvalidSyntaxError;

class PHPSyntaxCheckerService extends SyntaxCheckService {
  
  protected $cmd = ['php', '-l'];
  protected $exception_class = PHPInvalidSyntaxError::class;
}