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

use Arikaim\Core\Arikaim;
use Arikaim\Core\Http\Session;

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
    public function scopeRatingLogs($query, $id)
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
    public function hasLog($ratingId, $ip = null, $userId = null)
    {
        return \is_object($this->findLog($ratingId,$ip,$userId));                 
    } 
    
    /**
     * Add rating log
     *
     * @param integer $ratingId
     * @param float $value
     * @return Model
     */
    public function add($ratingId, $value)
    {
        $clientIp = Session::get('client_ip',null);      
        $userId = Arikaim::access()->getId();

        $log = $this->create([
            'rating_id'  => $ratingId,
            'value'      => $value,
            'ip'         => $clientIp,
            'user_id'    => $userId
        ]);

        return $log;
    } 

    /**
     * Find rating log
     *
     * @param string $ip
     * @param integer $userId
     * @param integer $ratingId
     * @return Model|null
     */
    public function findLog($ip = null, $userId = null, $ratingId = null)
    {
        $ratingId = (empty($ratingId) == true) ? $this->rating_id : $ratingId;
        $userId = (empty($userId) == true) ? Arikaim::access()->getId() : $userId;         
        $ip = (empty($ip) == true) ? Session::get('client_ip',null) : $ip;
           
        $model = $this
            ->where('rating_id','=',$ratingId)
            ->where('user_id','=',$userId)
            ->where('ip','=',$ip)
            ->orderBy('date_created', 'desc');

        return $model->first();
    }

    /**
     * Remove rating log
     *
     * @param string|integer $uuid
     * @return boolean
     */
    public function remove($uuid)
    {
        $log = $this->findById($uuid);
        
        return (\is_object($log) == true) ? $log->delete() : false;                     
    }

    /**
     * Calculate araverage value
     *
     * @return float
     */
    public function calcAverage()
    {
        $sum = (float)$this->sum('value');
        $count = (int)$this->count();
      
        return ($sum / $count);
    }
}
