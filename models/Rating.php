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
        'summary',
        'rated_count'     
    ];
    
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

    public function logs()
    {
        return $this->hasMany(RatingLogs::class,'rating_id');
    }

    public function remove($uuid)
    {
        $rating = $this->findById($uuid);
        if (is_object($rating) == true) {
            $rating->logs()->delete();
            return $rating->delete();
        }

        return false;     
    }

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

    public function isAllowed($id, $type)
    {
        $single_ip = Arikaim::options()->get('rating.single.ip',true);
        $single_user = Arikaim::options()->get('rating.single.user',true);
        $anonymous = Arikaim::options()->get('rating.allow.anonymous',false);

        if ($anonymous == false) {                        
            if (empty(Arikaim::auth()->getId()) == true) {               
                return false;
            }
        }
    
        if ($single_ip == true || $single_user == true) {
            $rating = $this->findRating($id,$type);
            if (is_object($rating) == true) {               
                $client_ip = ($single_ip == true) ? Arikaim::session()->get('client_id') : null;
                $user_id = ($single_user == true) ? Arikaim::auth()->getId() : null;
                $log = $rating->log()->findLog($client_ip,$user_id);

                if (is_object($log) == true) {                  
                    return false;
                }
            }        
        }

        return true;
    }

    public function log()
    {
        $log = new RatingLogs();
        $log->rating_id = $this->id;
        return $log;
    }

    public function hasRating($id, $type)
    {
        return is_object($this->findRating($id,$type));
    }

    public function findRating($id, $type)
    {
        return $this->where('reference_id','=',$id)->where('type','=',$type)->first();
    }

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
