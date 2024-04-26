<?php
/**
 * Arikaim
 *
 * @link        http://www.arikaim.com
 * @copyright   Copyright (c)  Konstantin Atanasov <info@arikaim.com>
 * @license     http://www.arikaim.com/license
 * 
*/
namespace Arikaim\Extensions\Rating;

use Arikaim\Core\Extension\Extension;

/**
 * Rating extension
*/
class Rating extends Extension
{
    /**
     * Install extension routes, events, jobs ..
     *
     * @return void
    */
    public function install()
    {
        // Api Routes
        $this->addApiRoute('GET','/api/rating/{id}','RatingApi','read');         
        $this->addApiRoute('POST','/api/rating/add','RatingApi','add');      
        // Control Panel      
        $this->addApiRoute('PUT','/api/rating/admin/update','RatingControlPanel','update','session');        
        $this->addApiRoute('DELETE','/api/rating/admin/delete/{uuid}','RatingControlPanel','delete','session');  
        $this->addApiRoute('DELETE','/api/rating/admin/logs/delete/{uuid}','RatingControlPanel','deleteLog','session');          
        // Create db tables
        $this->createDbTable('Rating');
        $this->createDbTable('RatingLogs'); 
        // Services
        $this->registerService('RatingService');       
        // Options
        $this->createOption('rating.allow.anonymous',true);
        $this->createOption('rating.single.user',true);
        $this->createOption('rating.single.ip',true);       
    }
    
    /**
     * UnInstall
     *
     * @return void
     */
    public function unInstall()
    {       
    }
}
