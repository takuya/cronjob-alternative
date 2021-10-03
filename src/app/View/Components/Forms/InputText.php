<?php

namespace App\View\Components\Forms;

use Illuminate\View\Component;

class InputText extends Component {
  
  public $name;
  public $value;
  public $placeholder;
  
  public function __construct( $name, $value, $placeholder = null ) {
    $this->name = $name;
    $this->value = $value;
    $this->placeholder = $placeholder;
  }
  
  /**
   * Get the view / contents that represent the component.
   * @return \Illuminate\Contracts\View\View|\Closure|string
   */
  public function render() {
    return view('components.forms.input-text');
  }
}
