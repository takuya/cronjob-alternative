<?php

namespace App\Http\Controllers;

use App\Models\CronEntry;
use Illuminate\Support\Str;
use App\Services\ScheduleService;
use App\Services\ArtisanCmdService;
use App\Console\Commands\CronEntryRun;
use App\Models\Services\CronLogService;
use App\Models\Repositories\CronEntryRepository;

class SchedulingController extends Controller {
  
  public function index() {
    $list = ScheduleService::getSchedulesAsArray('asc');
    return view('scheduling.index', ['entries' => $list]);
  }
}
