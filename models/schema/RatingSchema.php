<?php
/**
 * Arikaim
 *
 * @link        http://www.arikaim.com
 * @copyright   Copyright (c) 2016-2018 Konstantin Atanasov <info@arikaim.com>
 * @license     http://www.arikaim.com/license.html
 * 
*/
namespace Arikaim\Extensions\Rating\Models\Schema;

use Arikaim\Core\Db\Schema;

/**
 * Rating db table
 */
class RatingSchema extends Schema  
{    
    /**
     * Table name
     *
     * @var string
     */
    protected $table_name = "rating";

    /**
     * Create table
     *
     * @param \Arikaim\Core\Db\TableBlueprint $table
     * @return void
     */
    public function create($table) 
    {            
        // columns    
        $table->id();      
        $table->prototype('uuid');            
       // $table->string('ip')->nullable(true);    
        $table->integer('reference_id')->nullable(false);
        $table->string('reference_type')->nullable(false); 
       // $table->decimal('summary')->nullable(false)->default(0);

        // index
        $table->index('ip');
        $table->index(['reference_id','reference_type']);   
    }

    /**
     * Update table
     *
     * @param \Arikaim\Core\Db\TableBlueprint $table
     * @return void
     */
    public function update($table) 
    {              
    }
}
