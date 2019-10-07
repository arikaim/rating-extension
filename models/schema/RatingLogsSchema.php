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
 * RatingLogs db table
 */
class RatingLogsSchema extends Schema  
{    
    /**
     * Table name
     *
     * @var string
     */
    protected $table_name = "rating_logs";

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
        $table->relation('rating_id','rating');
        $table->userid();
        $table->string('ip')->nullable(false); 
        $table->decimal('value',2,2)->nullable(false);
        // index       
        $table->index('rating_id');   
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
