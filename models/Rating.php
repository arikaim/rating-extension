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

use Arikaim\Extensions\Tags\Models\TagsTranslations;

use Arikaim\Core\Traits\Db\Uuid;
use Arikaim\Core\Traits\Db\Find;

class Rating extends Model  
{
    use Uuid,       
        Find;
       
    protected $table = "rating";

    protected $fillable = [
        'position'      
    ];
   
    public $timestamps = false;
    
    public function remove($id)
    {
               
    }

}
