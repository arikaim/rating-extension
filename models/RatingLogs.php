<?php
/**
 * Arikaim
 *
 * @link        http://www.arikaim.com
 * @copyright   Copyright (c)  Konstantin Atanasov <info@arikaim.com>
 * @license     http://www.arikaim.com/license
 * 
*/
namespace Arikaim\Extensions\Rating\Models;

use Illuminate\Database\Eloquent\Model;

use Arikaim\Core\Db\Traits\Uuid;
use Arikaim\Core\Db\Traits\Find;
use Arikaim\Core\Db\Traits\DateCreated;
use Arikaim\Core\Db\Traits\UserRelation;

/**
 * Rating logs model class
 */
class RatingLogs extends Model  
{
    use Uuid,    
        UserRelation,   
        DateCreated,
        Find;
    
    /**
     * Table name
     *
     * @var string
     */
    protected $table = 'rating_logs';

    /**
     * Fillable attributes
     *
     * @var array
     */
    protected $fillable = [
        'ip',
        'rating_id',
        'date_created',
        'user_id',
        'value'      
    ];
   
    /**
     * Disable timestamps
     *
     * @var boolean
    */
    public $timestamps = false;
    
    /**
     * Ratign logs scope query
     *
     * @param Builder $query
     * @param integer $id
     * @return Builder
     */
    public function scopeRatingLogs($query, int $id)
    {
        return $query->where('rating_id','=',$id);
    } 

    /**
     * Return true if rating exist
     *
     * @param integer $ratingId
     * @param string $ip
     * @param integer $userId
     * @return boolean
     */
    public function hasLog($ratingId, $ip = null, $userId = null): bool
    {
        return ($this->findLog($ip,$userId,$ratingId) != null);                 
    } 
    
    /**
     * Add rating log
     *
     * @param integer $ratingId
     * @param float $value
     * @param int|null $userId
     * @param string|null $clientIp
     * @return Model
     */
    public function add(int $ratingId, float $value, ?int $userId = null, ?string $clientIp = null): ?object
    {
        return $this->create([
            'rating_id'  => $ratingId,
            'value'      => $value,
            'ip'         => $clientIp,
            'user_id'    => $userId
        ]);        
    } 

    /**
     * Find rating log
     *
     * @param string|null $ip
     * @param integer|null $userId
     * @param integer|null $ratingId
     * @return Model|null
     */
    public function findLog(?string $ip = null, ?int $userId = null, ?int $ratingId = null): ?object
    {
        $ratingId = $ratingId ?? $this->rating_id;                
        $model = $this->where('rating_id','=',$ratingId);

        if (empty($userId) == false) {
            $model = $model->where('user_id','=',$userId);
        }
        if (empty($ip) == false) {
            $model = $model->where('ip','=',$ip);
        }
       
        return $model->orderBy('date_created','desc')->first();
    }

    /**
     * Remove rating log
     *
     * @param string|integer $uuid
     * @return boolean
     */
    public function remove($uuid): bool
    {
        $log = $this->findById($uuid);
        
        return ($log != null) ? $log->delete() : false;                     
    }

    /**
     * Calculate araverage value
     *
     * @return float
     */
    public function calcAverage(): float
    {
        $sum = (float)$this->sum('value');
        $count = (int)$this->count();
      
        return (float)($sum / $count);
    }
}
