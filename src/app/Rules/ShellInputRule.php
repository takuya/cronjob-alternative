<?php

namespace App\Rules;

use Validator;
use Illuminate\Contracts\Validation\Rule;

class ShellInputRule implements Rule {
  
  /** @var \Illuminate\Validation\Validator $v */
  protected $v;
  
  /**
   * Determine if the validation rule passes.
   * @param string $attribute
   * @param mixed  $value
   * @return bool
   */
  public function passes( $attribute, $value ) {
    $this->v = Validator::make(
      $value,
      [
        'cmd'  => ['required', new ShellSupported()],
        'body' => ['required', new ShellSyntax($value['cmd'] ?? '')],
      ]);
    
    return $this->v->passes();
  }
  
  /**
   * Get the validation error message.
   * @return string
   */
  public function message() {
    /** @var \Illuminate\Support\MessageBag $msg */
    $msg = $this->v->messages();
    $ret = collect($msg->messages())->flatten()->join('');
    
    return $ret;
  }
}
