<?php
/**
 * Arikaim
 *
 * @link        http://www.arikaim.com
 * @copyright   Copyright (c)  Konstantin Atanasov <info@arikaim.com>
 * @license     http://www.arikaim.com/license
 * 
*/
namespace Arikaim\Extensions\Rating\Controllers;

use Arikaim\Core\Db\Model;
use Arikaim\Core\Controllers\ApiController;

/**
 * Rating control panel controler
*/
class RatingControlPanel extends ApiController
{
    /**
     * Init controller
     *
     * @return void
     */
    public function init()
    {
        $this->loadMessages('rating::admin.messages');
    }

    /**
     * Delete rating
     *
     * @param object $request
     * @param object $response
     * @param object $data
     * @return object
    */
    public function deleteController($request, $response, $data)
    { 
        $this->requireControlPanelPermission();

        $this->onDataValid(function($data) {
            $uuid = $data->get('uuid');
            $result = Model::Rating('rating')->remove($uuid);

            $this->setResponse($result,function() use($uuid) {            
                $this
                    ->message('delete')
                    ->field('uuid',$uuid);  
            },'errors.delete');
        }); 
        $data->validate();
    }

     /**
     * Delete rating
     *
     * @param object $request
     * @param object $response
     * @param object $data
     * @return object
    */
    public function deleteLogController($request, $response, $data)
    {
        $this->requireControlPanelPermission();

        $this->onDataValid(function($data) {
            $uuid = $data->get('uuid');
            $result = Model::RatingLogs('rating')->remove($uuid);

            $this->setResponse($result,function() use($uuid) {            
                $this
                    ->message('delete')
                    ->field('uuid',$uuid);  
            },'errors.delete');
        }); 
        $data->validate();
    }
}
