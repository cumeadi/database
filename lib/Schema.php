<?php
/* ===========================================================================
 * Opis Project
 * http://opis.io
 * ===========================================================================
 * Copyright 2013 Marius Sarca
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *    http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 * ============================================================================ */

namespace Opis\Database;

use Closure;
use PDOException;
use Opis\Database\Schema\CreateTable;
use Opis\Database\Schema\AlterTable;
use Opis\Database\Schema\Compiler;

class Schema
{
    /** @var    \Opis\Database\Connection   Connection. */
    protected $connection;
    
    protected $pdo;
    
    /**
     * Constructor
     *
     * @access public
     *
     * @param   \Opis\Database\Connection   $connection Connection.
     */
    
    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
        $this->pdo = $connection->pdo();
    }
    
    protected function replaceParams($query, array $params)
    {
        $pdo = $this->connection->pdo();
        
        return preg_replace_callback('/\?/', function($matches) use (&$params, $pdo){
            $param = array_shift($params);
            return (is_int($param) || is_float($param)) ? $param : $pdo->quote(is_object($param) ? get_class($param) : $param);
        }, $query);
    }
    
    protected function execute($command, array $params = array())
    {
        try
        {
            $statement = $this->pdo->prepare($query);
        }
        catch(PDOException $e)
        {
            throw new PDOException($e->getMessage() . ' [ ' . $this->replaceParams($query, $params) . ' ] ', (int) $e->getCode(), $e->getPrevious());
        }
        
        return $statement->execute($params);
    }
    
    public function create($table, Closure $callback)
    {
        $compiler = $this->connection->schemaCompiler();
        
        $schema = new CreateTable($table);
        
        $callback($schema);
        
        foreach($compiler->create($schema) as $result)
        {
            $this->execute($result['sql'], $result['params']);
        }
    }
    
    public function alter($table, Closure $callback)
    {
        $compiler = $this->connection->schemaCompiler();
        
        $schema = new AlterTable($table);
        
        $callback($schema);
        
        foreach($compiler->create($schema) as $result)
        {
            $this->execute($result['sql'], $result['params']);
        }
    }
    
    public function drop($table)
    {
        $compiler = $this->connection->schemaCompiler();
        
        $result = $compiler->drop($table);
        
        $this->execute($result['sql'], $result['paramse']);
    }
    
    public function truncate($table)
    {
        $compiler = $this->connection->schemaCompiler();
        
        $result = $compiler->truncate($table);
        
        $this->execute($result['sql'], $result['paramse']);
    }
    
}
