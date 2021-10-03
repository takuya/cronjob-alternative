<?php

namespace App\Rules;

use Illuminate\Support\Str;
use Illuminate\Contracts\Validation\Rule;

class FullPathRule implements Rule {
  
  /**
   * Determine if the validation rule passes.
   * @param string $attribute
   * @param mixed  $value
   * @return bool
   */
  public function passes( $attribute, $value ) {
    return Str::startsWith($value, '/');
  }
  
  /**
   * Get the validation error message.
   * @return string
   */
  public function message() {
    return 'FullPath should be started with "/".';
  }
}
