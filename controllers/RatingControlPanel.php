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
use Arikaim\Core\Controllers\ControlPanelApiController;

/**
 * Rating control panel controler
*/
class RatingControlPanel extends ControlPanelApiController
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
     *  Delete rating
     *
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @param \Psr\Http\Message\ResponseInterface $response
     * @param Validator $data
     * @return Psr\Http\Message\ResponseInterface
    */
    public function deleteController($request, $response, $data)
    { 
        $data
            ->validate(true);

        $uuid = $data->get('uuid');
        $result = Model::Rating('rating')->remove($uuid);

        $this->setResponse($result,function() use($uuid) {            
            $this
                ->message('delete')
                ->field('uuid',$uuid);  
        },'errors.delete');
    }
    
    /**
     *  Delete rating log
     *
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @param \Psr\Http\Message\ResponseInterface $response
     * @param Validator $data
     * @return Psr\Http\Message\ResponseInterface
    */
    public function deleteLogController($request, $response, $data)
    {
        $data
            ->validate(true);

        $uuid = $data->get('uuid');
        $rating = Model::RatingLogs('rating')->findById($uuid);
        $ratingId = $rating->rating_id;
        $rating->delete();

        $this->get('service')->get('rating')->updateRating($ratingId);

        $this
            ->message('delete')
            ->field('uuid',$uuid);  
    }
}
