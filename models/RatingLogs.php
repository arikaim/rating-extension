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

use Arikaim\Core\Traits\Db\Uuid;
use Arikaim\Core\Traits\Db\Find;
use Arikaim\Core\Traits\Db\DateCreated;
use Arikaim\Core\Traits\Db\UserRelation;

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
    protected $table = "rating_logs";

    protected $fillable = [
        'ip',
        'rating_id',
        'date_created',
        'user_id',
        'value'      
    ];
   
    public $timestamps = false;
    
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
        return is_object($this->findLog($ratingId,$ip,$userId));                 
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
        $userId = Arikaim::auth()->getId();

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
        $userId = (empty($userId) == true) ? Arikaim::auth()->getId() : $userId;         
        $ip = (empty($ip) == true) ? Session::get('client_ip',null) : $ip;
           
        $model = $this
            ->where('rating_id','=',$ratingId)
            ->where('user_id','=',$userId)
            ->where('ip','=',$ip)
            ->orderBy('date_created', 'desc');

        return $model->first();
    }
}
