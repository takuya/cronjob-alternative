<?php

namespace App\Http\Controllers;

use App\Models\CronEntry;
use Illuminate\Http\Request;
use App\Services\ArtisanCmdService;
use App\Console\Commands\CronEntryRun;
use App\Http\Requests\CronEntryRequest;
use App\Services\ProcessExec\ExecArgStruct;
use App\Services\ProcessExec\ProcessExecutor;

class CronJobController extends Controller {
  
  public function run_now( Request $request, $id ) {
    $entry = CronEntry::findOrFail($id);
    $param = $request->validate(
      [
        'run_now' => 'required|in:true,false',
      ]);
    $artisan_cmd = ArtisanCmdService::getCommandName(CronEntryRun::class);
    $php = find_php_path();
    // run artisan of CronEntryRun::class
    $arg = new ExecArgStruct();
    $arg->setCmd([$php, 'artisan', $artisan_cmd, $entry->id,'-Q']);
    $arg->setCwd(base_path('/'));
    $executor = new ProcessExecutor($arg);
    $executor->start();
    
    return response()->json(['status' => 'ok']);
  }
  
  public function update( CronEntryRequest $request, $id ) {
    $entry = CronEntry::findOrFail($id);
    $params = $request->validated();
    $entry->fill($params)->save();
    
    return redirect(route('user.cron.show', [$entry->id]));
  }
  
  public function pause( Request $request, $id ) {
    $entry = CronEntry::findOrFail($id);
    $param = $request->validate(
      [
        'enabled' => 'required|in:true,false',
      ]);
    if( $param['enabled'] === 'true' ) {
      $param['enabled'] = true;
    }
    if( $param['enabled'] === 'false' ) {
      $param['enabled'] = false;
    }
    $entry->enabled = $param['enabled'];
    $entry->save();
    $entry->refresh();
    
    return $entry;
  }
  
  public function index( Request $request ) {
    $table_headers = ['id', 'name', 'shell', 'command', 'cron_date', 'enabled'];
    $entries = CronEntry::all();
    
    return response()
      ->view('cron.index', ['entries' => $entries, 'headers' => $table_headers])
      ->header("pragma", "no-cache")
      ->header("Cache-Control", "must-revalidate");
  }
  
  public function show( CronEntry $cron ) {
    $last_log = $cron->getLatestLog(5);
    
    return view('cron.show', ['entry' => $cron, 'last_log' => $last_log]);
  }
  
  public function edit( $id ) {
    $entry = CronEntry::findOrFail($id);
    
    return view('cron.edit', ['entry' => $entry]);
  }
  
  public function destroy( $id ) {
    $entry = CronEntry::findOrFail($id);
    $entry->delete();
    
    return redirect(route('user.cron.index'));
  }
  
  public function create() {
    return view('cron.create', ['entry' => new CronEntry()]);
  }
  
  public function store( CronEntryRequest $request ) {
    $entry = new CronEntry();
    $params = $request->validated();
    $entry->fill($params);
    $entry->owner()->associate(auth()->user());
    $entry->save();
    
    return redirect(route('user.cron.show', [$entry->id]));
  }
}
