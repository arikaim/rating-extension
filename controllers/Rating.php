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
use Arikaim\Core\View\Template;

/**
 * Rating api controler
*/
class Rating extends ApiController
{
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
            $tag = Model::Rating('rating')->findById($id);          
            $data = array_merge($translation->toArray(),$tag->toArray());
        });

        $data->validate();
    }
}
