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
use Arikaim\Extensions\Rating\Models\RatingLogs;

use Arikaim\Core\Db\Traits\Uuid;
use Arikaim\Core\Db\Traits\Find;

class Rating extends Model  
{
    use Uuid,       
        Find;
       
    /**
     * Db table name
     *
     * @var string
     */
    protected $table = "rating";

    /**
     * Fillable attributes
     *
     * @var array
     */
    protected $fillable = [
        'reference_id',
        'type',
        'summary',
        'rated_count'     
    ];
    
    /**
     * Disable timestamps
     *
     * @var boolean
     */
    public $timestamps = false;
    
    /**
     * Mutator (get) for average attribute.
     *
     * @return array
     */
    public function getAverageAttribute()
    {
        return ($this->summary / $this->rated_count);
    }

    /**
     * Rating logs relation
     *
     * @return mixed
     */
    public function logs()
    {
        return $this->hasMany(RatingLogs::class,'rating_id');
    }

    /**
     * Remove rating
     *
     * @param string|integer $uuid
     * @return boolean
     */
    public function remove($uuid)
    {
        $rating = $this->findById($uuid);
        if (is_object($rating) == true) {
            $rating->logs()->delete();
            return $rating->delete();
        }

        return false;     
    }

    /**
     * Add rating
     *
     * @param integer $id
     * @param string $type
     * @param float $value
     * @return Model
     */
    public function add($id, $type, $value)
    {
        $value = number_format($value,2);
        $info = ['reference_id' => $id,'type' => $type, 'summary' => $value];

        $rating = $this->findRating($id,$type);
        if (is_object($rating) == true) {
            $rating->increment('rated_count');
            $info['summary'] = $rating->summary + $value;         
            $rating->update($info);
        } else {
            $rating = $this->create($info);
        }
        $rating->log()->add($rating->id,$value);

        return $rating;
    }

    /**
     * Return true if rating is allowed 
     *
     * @param integer $id
     * @param string $type
     * @return boolean
     */
    public function isAllowed($id, $type)
    {
        $uniqueIp = Arikaim::options()->get('rating.single.ip',true);
        $singleUser = Arikaim::options()->get('rating.single.user',true);
        $anonymous = Arikaim::options()->get('rating.allow.anonymous',false);

        if ($anonymous == false) {                        
            if (empty(Arikaim::access()->getId()) == true) {               
                return false;
            }
        }
    
        if ($uniqueIp == true || $singleUser == true) {
            $rating = $this->findRating($id,$type);
            if (is_object($rating) == true) {               
                $clientIp = ($uniqueIp == true) ? Session::get('client_id') : null;
                $userId = ($singleUser == true) ? Arikaim::access()->getId() : null;
                $log = $rating->log()->findLog($clientIp,$userId);

                if (is_object($log) == true) {                  
                    return false;
                }
            }        
        }

        return true;
    }

    /**
     *  Create rating log Model
     *
     * @return Model
     */
    public function log()
    {
        $log = new RatingLogs();
        $log->rating_id = $this->id;
        return $log;
    }

    /**
     * Return true if rating exist
     *
     * @param integer $id
     * @param string $type
     * @return boolean
     */
    public function hasRating($id, $type)
    {
        return is_object($this->findRating($id,$type));
    }

    /**
     * Find rating
     *
     * @param integer $id
     * @param string $type
     * @return Model
     */
    public function findRating($id, $type)
    {
        return $this->where('reference_id','=',$id)->where('type','=',$type)->first();
    }

    /**
     * Update rating
     *
     * @param integer $id
     * @param string $type
     * @param float $value
     * @return boolean
     */
    public function updateRating($id, $type, $value)
    {
        $rating = $this->findRating($id,$type);
        if (is_object($rating) == false) {
            return false;
        }
        $value = number_format($value,2);
        $info = ['reference_id' => $id,'type' => $type, 'summary' => $value];

        return $rating->update($info);
    }
}
