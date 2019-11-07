<?php
/**
 * Arikaim
 *
 * @link        http://www.arikaim.com
 * @copyright   Copyright (c) 2016-2018 Konstantin Atanasov <info@arikaim.com>
 * @license     http://www.arikaim.com/license
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
    protected $tableName = "rating";

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
        $table->integer('reference_id')->nullable(false);
        $table->string('type')->nullable(false); 
        $table->decimal('summary',15,2)->nullable(false)->default(0.00);
        $table->integer('rated_count')->nullable(false)->default(1);
        // index       
        $table->unique(['reference_id','type']);   
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
