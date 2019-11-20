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
use Arikaim\Core\Arikaim;

/**
 * Rating api controler
*/
class Rating extends ApiController
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
     * Read rating
     *
     * @param object $request
     * @param object $response
     * @param Validator $data
     * @return object
    */
    public function readController($request, $response, $data)
    {
        $this->onDataValid(function($data) {
            $id = $data->get('id');
            $rating = Model::Rating('rating')->findById($id);

            $this->setResponse(is_object($rating),function() use($rating) {                  
                $this
                    ->message('read')
                    ->field('rating',$rating->toArray());                  
            },'errors.read');                     
        });
        $data->validate();
    }

    /**
     * Add rating
     *
     * @param object $request
     * @param object $response
     * @param Validator $data
     * @return object
    */
    public function addController($request, $response, $data)
    {
        $this->onDataValid(function($data) use ($request) {
            $id = $data->get('id');
            $type = $data->get('type');
            $value = $data->get('value');

            $rating = Model::Rating('rating');
        
            if ($rating->isAllowed($id,$type) == false) {
                if (empty(Arikaim::auth()->getId()) == true) {
                    $this->error('errors.anonymous');
                } else {
                    $this->error('errors.single');
                }
                return;
            }

            $rating = $rating->add($id,$type,$value);
            $this->setResponse(is_object($rating),function() use($rating) {                  
                $this
                    ->message('add')
                    ->field('average',number_format($rating->average,2))
                    ->field('uuid',$rating->uuid);                  
            },'errors.add');                     
        });
        $data
            ->addRule('text:min=2','type')
            ->addRule('text:min=1','id')
            ->addRule('text:min=1','value')
            ->validate();
    }
}
