<?php
/**
 * Arikaim
 *
 * @link        http://www.arikaim.com
 * @copyright   Copyright (c) 2016-2018 Konstantin Atanasov <info@arikaim.com>
 * @license     http://www.arikaim.com/license.html
 * 
*/
namespace Arikaim\Extensions\Rating\Models;

use Illuminate\Database\Eloquent\Model;

use Arikaim\Core\Arikaim;

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
       
    protected $table = "rating_logs";

    protected $fillable = [
        'ip',
        'rating_id',
        'date_created',
        'user_id',
        'value'      
    ];
   
    public $timestamps = false;
    
    public function hasLog($rating_id, $ip = null, $user_id = null)
    {
        return is_object($this->findLog($rating_id,$ip,$user_id));                 
    } 
    
    public function add($rating_id, $value)
    {
        $client_ip = Arikaim::session()->get('client_ip',null);      
        $user_id = Arikaim::auth()->getId();

        $log = $this->create([
            'rating_id'  => $rating_id,
            'value'      => $value,
            'ip'         => $client_ip,
            'user_id'    => $user_id
        ]);

        return $log;
    } 

    public function findLog($ip = null, $user_id = null, $rating_id = null)
    {
        $rating_id = (empty($rating_id) == true) ? $this->rating_id : $rating_id;
        if (empty($user_id) == true) {
            $user_id = Arikaim::auth()->getId();
        }
        if (empty($ip) == true) {
            $ip = Arikaim::session()->get('client_ip',null);      
        }

        $model = $this
            ->where('rating_id','=',$rating_id)
            ->where('user_id','=',$user_id)
            ->where('ip','=',$ip)
            ->orderBy('date_created', 'desc');

        return $model->first();
    }
}
