<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use App\Services\SyntaxCheck\SyntaxCheckService;
use App\Services\SyntaxCheck\Exceptions\ShellSyntaxError;

class ShellSyntax implements Rule {
  
  protected $shell;
  /** @var ShellSyntaxError */
  protected $ex;
  
  public function __construct( $shell ) {
    $this->shell = $shell;
  }
  
  /**
   * Determine if the validation rule passes.
   * @param string $attribute
   * @param mixed  $value
   * @return bool
   */
  public function passes( $attribute, $value ) {
    try {
      SyntaxCheckService::validate($value, $this->shell);
    } catch (ShellSyntaxError $ex) {
      $this->ex = $ex;
      return false;
    }
    
    return true;
  }
  
  /**
   * Get the validation error message.
   * @return string
   */
  public function message() {
    $msg =  $this->shell." syntax Error. ( {$this->ex->getMessage()} )";
    return $msg;
  }
}
