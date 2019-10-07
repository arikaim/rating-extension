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

use Arikaim\Core\Traits\Db\Uuid;
use Arikaim\Core\Traits\Db\Find;

class RatingLogs extends Model  
{
    use Uuid,       
        Find;
       
    protected $table = "rating_logs";

    protected $fillable = [
        'ip',
        'rating_id',
        'value'      
    ];
   
    public $timestamps = false;
    
    public function hasLog($rating_id, $user_id, $ip)
    {
               
    }   
}
