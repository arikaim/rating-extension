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
use Arikaim\Core\Utils\ClientIp;

/**
 * Rating api controler
*/
class RatingApi extends ApiController
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
     *  Read rating
     *
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @param \Psr\Http\Message\ResponseInterface $response
     * @param Validator $data
     * @return Psr\Http\Message\ResponseInterface
    */
    public function read($request, $response, $data)
    {
        $data->validate(true);

        $id = $data->get('id');
        $rating = Model::Rating('rating')->findById($id);
        if ($rating == null) {
            $this->error('errors.read','Rating id not valid');
            return false;
        }
           
        $this
            ->message('read')
            ->field('rating',$rating->toArray());                                                 
    }

    /**
     * Add rating
     *
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @param \Psr\Http\Message\ResponseInterface $response
     * @param Validator $data
     * @return Psr\Http\Message\ResponseInterface
    */
    public function add($request, $response, $data)
    {
        $data
            ->addRule('text:min=2','type')
            ->addRule('text:min=1','id')
            ->addRule('text:min=1','value')
            ->validate(true);

        $id = $data->get('id');
        $type = $data->get('type');
        $value = $data->get('value');

        $rating = Model::Rating('rating');
        $userId = $this->getUserId();
        $clientIp = ClientIp::getClientIpAddress($request);

        if ($this->get('service')->get('rating')->isAllowed($id,$type,$userId,$clientIp) == false) {
            if (empty($userId) == true) {
                $this->error('errors.anonymous');
            } else {
                $this->error('errors.single');
            }
            return;
        }
        
        $rating = $rating->add($id,$type,$value,$userId,$clientIp);
        if ($rating == null) {
            $this->error('errors.add');
            return false;
        }

        $this
            ->message('add')
            ->field('average',\number_format($rating->average,2))
            ->field('uuid',$rating->uuid);                                          
    }
}
