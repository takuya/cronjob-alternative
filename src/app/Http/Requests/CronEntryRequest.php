<?php

namespace App\Http\Requests;

use Str;
use App\Rules\FullPathRule;
use App\Rules\ShellInputRule;
use App\Rules\CronExpressionRule;
use App\Rules\PosixUserExistsRule;
use Illuminate\Foundation\Http\FormRequest;

class CronEntryRequest extends FormRequest {
  
  /**
   * Determine if the user is authorized to make this request.
   * @return bool
   */
  public function authorize() {
    return true;
  }
  
  public function validated() {
    return $this->toCronEntryFilleArray();
  }
  
  public function toCronEntryFilleArray() {
    $params = parent::validated();
    $params['command'] = $params['shell']['body'];
    $params['shell'] = $params['shell']['cmd'];
    if( $params['env'] ) {
      $params['env'] = json_decode($params['env']);
    }
    $params['command'] = Str::of($params['command'])->split('/\r\n/')->join("\n");
    
    return $params;
  }
  
  /**
   * Get the validation rules that apply to the request.
   * @return array
   */
  public function rules() {
    return [
      'name'        => 'required|min:3',
      'cron_date'   => ['required', new CronExpressionRule()],
      'shell'       => ['required', 'array', 'array:cmd,body', new ShellInputRule()],
      'cwd'         => ['nullable', new FullPathRule()],
      'env'         => 'nullable|json',
      'user'        => ['nullable', new PosixUserExistsRule()],
      'random_wait' => 'nullable|integer|min:0|max:3600',
      'comment'     => 'nullable|max:5120',
    ];
  }
}
