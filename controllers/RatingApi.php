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
use Arikaim\Core\Middleware\ClientIpMiddleware;

/**
 * Rating api controler
*/
class RatingApi extends ApiController
{
    /**
    * Constructor
    * 
    * @param Container|null $container
    */
    public function __construct($container = null) 
    {        
        $this->addMiddleware(ClientIpMiddleware::class);

        parent::__construct($container);
    }

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
    public function readController($request, $response, $data)
    {
        $this->onDataValid(function($data) {
            $id = $data->get('id');
            $rating = Model::Rating('rating')->findById($id);

            $this->setResponse(\is_object($rating),function() use($rating) {                  
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
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @param \Psr\Http\Message\ResponseInterface $response
     * @param Validator $data
     * @return Psr\Http\Message\ResponseInterface
    */
    public function addController($request, $response, $data)
    {
        $this->onDataValid(function($data) use ($request) {
            $id = $data->get('id');
            $type = $data->get('type');
            $value = $data->get('value');

            $rating = Model::Rating('rating');
            $curretnUserId = $this->getUserId();
            $clientIp = $request->getAttribute('client_ip');   

            $options = $this->get('options')->searchOptions('rating.',true);

            if ($rating->isAllowed($id,$type,$curretnUserId,$clientIp,$options) == false) {
                if (empty($curretnUserId) == true) {
                    $this->error('errors.anonymous');
                } else {
                    $this->error('errors.single');
                }
                return;
            }
            
            $rating = $rating->add($id,$type,$value,$curretnUserId,$clientIp);
            $this->setResponse(\is_object($rating),function() use($rating) {                  
                $this
                    ->message('add')
                    ->field('average',\number_format($rating->average,2))
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
