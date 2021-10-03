<?php

namespace App\Http\Middleware;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Auth\Middleware\Authenticate as Middleware;

class Authenticate extends Middleware {
  
  /**
   * Get the path the user should be redirected to when they are not authenticated.
   * @param Request $request
   * @return string|null
   */
  protected function redirectTo( $request ) {
    if( ! $request->expectsJson() ) {
      return route('login');
    }
  }
  
  protected function authenticate( $request, array $guards ) {
    // 開発時は、seeder のユーザーで自動ログインにする。
    if( preg_match('/dev|local/', config('app.env')) ) {
      Auth::login(User::find(1), true);
    }
    parent::authenticate($request, $guards);
  }
}
