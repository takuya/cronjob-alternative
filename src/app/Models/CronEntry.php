<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Cron\CronExpression;
use App\Exceptions\InvalidCronExpression;
use Illuminate\Database\Eloquent\Builder;
use Eloquent;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Repositories\CronEntryRepository;
use App\Services\SyntaxCheck\CommandExistsCheckerService;
use App\Services\SyntaxCheck\BashSyntaxCheckerService;
use App\Services\SyntaxCheck\PHPSyntaxCheckerService;

/**
 * App\Models\CronEntry
 *
 * @property int         $id
 * @property string      $cron_date
 * @property string      $command
 * @property string|null $user
 * @property string|null $env
 * @property int         $enabled
 * @property string|null $name
 * @property string|null $comment
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @method static Builder|CronEntry newModelQuery()
 * @method static Builder|CronEntry newQuery()
 * @method static Builder|CronEntry query()
 * @method static Builder|CronEntry whereCommand( $value )
 * @method static Builder|CronEntry whereComment( $value )
 * @method static Builder|CronEntry whereCreatedAt( $value )
 * @method static Builder|CronEntry whereCronDate( $value )
 * @method static Builder|CronEntry whereEnabled( $value )
 * @method static Builder|CronEntry whereEnv( $value )
 * @method static Builder|CronEntry whereId( $value )
 * @method static Builder|CronEntry whereName( $value )
 * @method static Builder|CronEntry whereUpdatedAt( $value )
 * @method static Builder|CronEntry whereUser( $value )
 * @mixin Eloquent
 * @property string      $shell
 * @method static Builder|CronEntry whereShell( $value )
 * @property string|null $cwd
 * @method static Builder|CronEntry whereCwd( $value )
 * @property int $owner_id
 * @method static Builder|CronEntry whereOwnerId($value)
 * @property-read \App\Models\User $owner
 * @property int|null $random_wait
 * @method static Builder|CronEntry whereRandomWait($value)
 */
class CronEntry extends Model {
  
  protected $guarded = [];
  
  //use HasFactory;
  protected $casts = [
    'env' => 'array',
    'enabled'=>'bool',
  ];
  protected $hidden =['owner_id'];
  
  public static function getEnabledEntries () {
    return CronEntry::where( 'enabled', true )->get();
  }
  
  public function save ( array $options = [] ): bool {
    $this->name = $this->name ?? get_classname_base( __CLASS__ );
    $this->checkShellCommandExists( $this->shell );
    $this->checkSyntax( $this->command );
    return parent::save( $options );
  }
  
  protected function checkShellCommandExists ( $value ) {
    $value && CommandExistsCheckerService::validate( $value );
  }
  
  protected function checkSyntax ( $value ) {
    if ( $this->shell == null || preg_match( '/bash$/', $this->shell ) ) {
      BashSyntaxCheckerService::validate( $value );
    } else if ( preg_match( '/php/', $this->shell ) ) {
      PHPSyntaxCheckerService::validate( $value );
    }
  }
  
  protected function setCronDateAttribute ( $value ) {
    if ( !CronExpression::isValidExpression( $value ) ) {
      throw new InvalidCronExpression( "Invalid expression '{$value}'" );
    }
    $this->attributes['cron_date'] = $value;
  }
  public function owner(): BelongsTo {
    return $this->belongsTo(User::class);
  }
  public function getOwnerAttribute(){
    return $this->owner()->first();
  }
  public function getNextDueDate($timezone=null){
    $timezone = $timezone??config( 'app.timezone' );
    return CronEntryRepository::nextDueDate($this->cron_date,$timezone);
  }
  public function getLatestLog($limit=10){
    $logs = CronLog::where( 'cron_entry', 'like', "%\"id\":{$this->id}%" )
           ->where( 'name', $this->name )
           ->orderBy( 'updated_at', 'desc' )
           ->limit( $limit )
           ->get();
    return $logs;
  }
  
}
