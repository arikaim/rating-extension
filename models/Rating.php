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

use Arikaim\Extensions\Rating\Models\RatingLogs;

use Arikaim\Core\Db\Traits\Uuid;
use Arikaim\Core\Db\Traits\Find;
use Arikaim\Core\Db\Traits\Status;
use Arikaim\Core\Db\Traits\PolymorphicRelations;

/**
 * Rating model class
 */
class Rating extends Model  
{
    use Uuid,  
        Status,
        PolymorphicRelations,     
        Find;
       
    /**
     * Db table name
     *
     * @var string
     */
    protected $table = 'rating';

    /**
     * Fillable attributes
     *
     * @var array
     */
    protected $fillable = [
        'relation_type',
        'relation_id',
        'summary',        
        'status',
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
     * @return float
     */
    public function getAverageAttribute()
    {
        return ($this->rated_count > 0 && $this->summary > 0) ? ($this->summary / $this->rated_count) : 0;
    }

    /**
     * Rating logs relation
     *
     * @return Relation|null
     */
    public function logs()
    {
        return $this->hasMany(RatingLogs::class,'rating_id');
    }

    /**
     * Remove rating
     *
     * @param string|integer|null $id
     * @return boolean
     */
    public function remove($id = null): bool
    {
        $rating = (empty($id) == true) ? $this : $this->findById($id);     
        if ($rating == null) {
            return false;  
        }
        $rating->logs()->delete();
        $result = $rating->delete();

        return ($result !== false);
    }

    /**
     * Find rating log
     *
     * @param integer|null $userId
     * @param string|null  $clientIp
     * @return Model|null
     */
    public function findRatingLog(?int $userId = null, ?string $clientIp = null): ?object
    {
        $query = $this->logs;
        if (empty($userId) == false) {
            $query = $query->where('user_id','=',$userId);
        }
        if (empty($clientIp) == false) {
            $query = $query->where('ip','=',$clientIp);
        }

        return $query->first();
    }

    /**
     * Add rating
     *
     * @param integer $id
     * @param string $type
     * @param float $value
     * @return Model|null
     */
    public function add(int $id, string $type, float $value, ?int $userId = null, ?string $clientIp = null): ?object
    {
        $value = \number_format($value,2);
       
        $rating = $this->findRating($id,$type);
        if ($rating != null) {
            // update rating           
            $rating->update([
                'summary' => $rating->summary + $value
            ]);
            $rating->increment('rated_count');            
        } else {
            // create rating
            $rating = $this->create([
                'relation_id'   => $id,
                'relation_type' => $type,                
                'summary'       => $value
            ]);

            if ($rating == null) {
                return null;
            }
        }
        // add log
        $rating->log()->add($rating->id,$value,$userId,$clientIp);
        
        return $rating;
    }

    /**
     * Create rating log Model
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
    public function hasRating(int $id, string $type): bool
    {
        return ($this->findRating($id,$type) != null);
    }

    /**
     * Find rating
     *
     * @param integer $id
     * @param string $type
     * @return Model|null
     */
    public function findRating(int $id, string $type): ?object
    {
        return $this->where('relation_id','=',$id)->where('relation_type','=',$type)->first();       
    }

    /**
     * Update rating
     *
     * @param integer $id
     * @param string $type
     * @param float $value
     * @return boolean
     */
    public function updateRating(int $id, string $type, float $value): bool
    {
        $rating = $this->findRating($id,$type);
        if ($rating == null) {
            return false;
        }

        $value = \number_format($value,2);
        $result = $rating->update([
            'reference_id' => $id,
            'type'         => $type,
            'summary'      => $value
        ]);

        return ($result !== false);
    }
}
