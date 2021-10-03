<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class PosixUserExistsRule implements Rule {
  /**
   * Create a new rule instance.
   *
   * @return void
   */
  public function __construct () {
    //
  }
  
  /**
   * Determine if the validation rule passes.
   *
   * @param string $attribute
   * @param mixed  $value
   * @return bool
   */
  public function passes ( $attribute, $value ) {
    if ( function_exists( 'posix_getgrnam' ) ) {
      return posix_getgrnam( $value ) !== false;
    } else {
      return true;
    }
  }
  
  /**
   * Get the validation error message.
   *
   * @return string
   */
  public function message () {
    return 'User not found in System(getent,/etc/passwd).';
  }
}
