<?php
/**
 * Arikaim
 *
 * @link        http://www.arikaim.com
 * @copyright   Copyright (c) 2016-2018 Konstantin Atanasov <info@arikaim.com>
 * @license     http://www.arikaim.com/license.html
 * 
*/
namespace Arikaim\Extensions\Rating;

use Arikaim\Core\Packages\Extension\Extension;

/**
 * Rating extension
*/
class Rating extends Extension
{
    /**
     * Install extension routes, events, jobs ..
     *
     * @return boolean
    */
    public function install()
    {
        // Api Routes
        $this->addApiRoute('GET','/api/rating/{id}','Rating','read');         
        // Control Panel
        $this->addApiRoute('POST','/api/rating/admin/add','RatingControlPanel','add','session');      
        $this->addApiRoute('DELETE','/api/rating/admin/delete/{uuid}','RatingControlPanel','delete','session');          
        // Create db tables
        $this->createDbTable('RatingSchema');
        $this->createDbTable('RatingLogsSchema');
        
        return true;
    }
    
    public function unInstall()
    {
        $this->dropDbTable('RatingLogsSchema');
        $this->dropDbTable('RatingSchema');        
    }
}
