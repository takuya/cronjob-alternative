<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Cron\CronExpression;

class CronExpressionRule implements Rule {
  
  /**
   * Determine if the validation rule passes.
   *
   * @param string $attribute
   * @param mixed  $value
   * @return bool
   */
  public function passes ( $attribute, $value ) {
    return CronExpression::isValidExpression( $value );
  }
  
  /**
   * Get the validation error message.
   *
   * @return string
   */
  public function message () {
    return 'Invalid CronExpression';
  }
}
