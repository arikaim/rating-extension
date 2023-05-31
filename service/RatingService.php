<?php
/**
 * Arikaim
 *
 * @link        http://www.arikaim.com
 * @copyright   Copyright (c)  Konstantin Atanasov <info@arikaim.com>
 * @license     http://www.arikaim.com/license
 * 
*/
namespace Arikaim\Extensions\Rating\Service;

use Arikaim\Core\Db\Model;
use Arikaim\Core\Service\Service;
use Arikaim\Core\Service\ServiceInterface;

/**
 * Rating service class
*/
class RatingService extends Service implements ServiceInterface
{
    /**
     * Init service
    */
    public function boot()
    {
        $this->setServiceName('rating');
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
        return (bool)Model::Rating('rating')->hasRating($id,$type);
    }

    /**
     * Return true if rating is allowed 
     *
     * @param integer $id
     * @param string $type
     * @param int|null $userId
     * @param string|null $clientIp
     * @return boolean
     */
    public function isAllowed(int $id, string $type, ?int $userId, ?string $clientIp): bool
    {
        global $container;

        $uniqueIp = (bool)$container->get('options')->get('rating.single.ip',false);
        $singleUser = (bool)$container->get('options')->get('rating.single.user',false); 
        $anonymous = (bool)$container->get('options')->get('rating.allow.anonymous',false);
    
        if (($anonymous == false) && (empty($currentUserId) == true)) {   
            // deny for anonymous                                          
            return false;          
        }
    
        if ($uniqueIp == true || $singleUser == true) {
            $rating = Model::Rating('rating')->findRating($id,$type);
            if ($rating == null) { 
                return true;
            }
     
            return ($rating->findRatingLog($userId,$clientIp) == null);
        }

        return true;
    }

    public function updateRating(int $id): bool
    {
        $rating = Model::Rating('rating')->findById($id);
        if ($rating == null) { 
            return false;
        }

        $summary = $rating->logs()->sum('value');
        $count = $rating->logs()->count();

        return (bool)$rating->update([
            'summary'     => $summary,
            'rated_count' => $count
        ]);
    }
}
