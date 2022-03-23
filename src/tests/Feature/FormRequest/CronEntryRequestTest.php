<?php

namespace Tests\Feature\FormRequest;

use Tests\TestCase;
use App\Http\Requests\CronEntryRequest;
use Illuminate\Routing\Redirector;

class CronEntryRequestTest extends TestCase {
  public function test_cron_entry_request_with_CRLF () {
    $body =<<<'EOS'
    echo hello world;
    function sample (){
        echo sample;
    }
    if [ -a  $a ] ; then
      echo true;
    fi
    EOS;
    $body= preg_replace("/\r\n|\r|\n/", "\r\n", $body);
    $param = [
      "name" => __FUNCTION__,
      "cron_date" => '* * * * *',
      "shell" => [
        'cmd' => 'bash',
        'body' => $body
      ],
      "cwd" => null,
      "env" => null,
      "user" => null,
      "random_wait" => null,
      "comment" => null,
    ];
    $req = new CronEntryRequest($param);
    $req->setContainer(app())
      ->setRedirector(app(Redirector::class))
      ->validateResolved();
    $this->assertTrue(true);// no exception occured.
  }
  
}