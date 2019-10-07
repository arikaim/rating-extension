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

use Arikaim\Extensions\Rating\Models\RatingLogs;

use Arikaim\Core\Traits\Db\Uuid;
use Arikaim\Core\Traits\Db\Find;

class Rating extends Model  
{
    use Uuid,       
        Find;
       
    protected $table = "rating";

    protected $fillable = [
        'reference_id',
        'type',
        'summary'      
    ];
    
    public $timestamps = false;
    
    public function logs()
    {

    }

    public function remove($id, $type, $remove_logs = true)
    {
               
    }

    public function add($id, $type, $value, $add_log = true)
    {

    }

    public function setRating($id, $type, $value)
    {

    }
}
