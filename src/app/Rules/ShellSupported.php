<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use App\Services\SyntaxCheck\SyntaxCheckService;

class ShellSupported implements Rule {
  
  /**
   * Determine if the validation rule passes.
   * @param string $attribute
   * @param mixed  $value
   * @return bool
   */
  public function passes( $attribute, $value ) {
    return SyntaxCheckService::supported($value);
  }
  
  /**
   * Get the validation error message.
   * @return string
   */
  public function message() {
    return 'Not supported';
  }
}
