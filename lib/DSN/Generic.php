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

namespace Opis\Database\DSN;

use PDO;
use Closure;
use Opis\Database\Connection;

class Generic extends Connection
{
    protected $compilerConstructor;
    
    public function __construct($dsn, $username = null, $password = null)
    {
        $this->dsn = $dsn;
        parent::__construct('', $username, $password);
    }
    
    public function setCompiler(Closure $compiler)
    {
        $this->compilerConstructor = $compiler;
        return $this;
    }
    
    public function compiler()
    {
        if($this->compilerConstructor !== null)
        {
            return $this->compilerConstructor();
        }
        
        switch($this->pdo()->getAttribute(PDO::ATTR_DRIVER_NAME))
        {
            case 'mysql':
                return new \Opis\Database\Compiler\MySQL();
            case 'dblib':
            case 'mssql':
            case 'sqlsrv':
            case 'sybase':
                return new \Opis\Database\Compiler\SQLServer();
            case 'oci':
            case 'oracle':
                return new \Opis\Database\Compiler\Oracle();
            case 'firebird':
                return new \Opis\Database\Compiler\Firebird();
            case 'db2':
            case 'ibm':
            case 'odbc':
                return new \Opis\Database\Compiler\DB2();
            case 'nuodb':
                return new \Opis\Database\Compiler\NuoDB();
            default:
                return new \Opis\Database\SQL\Compiler();
        }
    }
}