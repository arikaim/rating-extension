<?php
/**
 * Arikaim
 *
 * @link        http://www.arikaim.com
 * @copyright   Copyright (c) 2016-2018 Konstantin Atanasov <info@arikaim.com>
 * @license     http://www.arikaim.com/license.html
 * 
*/
namespace Arikaim\Extensions\Rating\Controllers;

use Arikaim\Core\Db\Model;
use Arikaim\Core\Controllers\ApiController;
use Arikaim\Core\Arikaim;

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
     * Add new rating
     *
     * @param object $request
     * @param object $response
     * @param object $data
     * @return object
    */
    public function addController($request, $response, $data) 
    {       
        $this->requireControlPanelPermission();
        
        $this->onDataValid(function($data) {
            $category = Model::Tags('category');
                                                
            $model = $category->create($data->toArray());

            if (is_object($model) == true) {                      
                $result = $model->saveTranslation($data->slice(['title','description']),$data['language']);                              
            } else {
                $result = false;
            }
            $this->setResponse($result,function() use($model,$data) {                                
                $this
                    ->message('add')
                    ->field('id',$model->id)
                    ->field('uuid',$model->uuid);           
            },'errors.add');
        });
        $data           
            ->addRule('text:min=2','tag')
            ->addRule('text:min=2|max=2','language')
            ->validate();       
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
}
