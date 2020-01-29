<?php
/**
 * Arikaim
 *
 * @link        http://www.arikaim.com
 * @copyright   Copyright (c)  Konstantin Atanasov <info@arikaim.com>
 * @license     http://www.arikaim.com/license
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
    protected $tableName = "rating_logs";

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
        $table->dateCreated();  
        $table->relation('rating_id','rating');
        $table->userId();
        $table->string('ip')->nullable(true); 
        $table->float('value',5,2)->nullable(false);        
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
