<?php
/* ===========================================================================
 * Copyright 2013-2016 The Opis Project
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

namespace Opis\Database\Schema;

class CreateColumn extends BaseColumn
{
    /** @var    string */
    protected $table;

    /**
     * Constructor
     * 
     * @param   CreateTable $table
     * @param   string      $name
     * @param   string      $type
     */
    public function __construct(CreateTable $table, $name, $type)
    {
        $this->table = $table;
        parent::__construct($name, $type);
    }

    /**
     * @return  string
     */
    public function getTable()
    {
        return $this->table;
    }

    /**
     * @param   string|null $name
     * @return  $this
     */
    public function autoincrement($name = null)
    {
        $this->table->autoincrement($this, $name === null ? $this->name : $name);
        return $this;
    }

    /**
     * @param string|null $name
     * @return $this
     */
    public function primary($name = null)
    {
        $this->table->primary($name === null ? $this->name : $name, $this->name);
        return $this;
    }

    /**
     * @param string|null $name
     * @return $this
     */
    public function unique($name = null)
    {
        $this->table->unique($name === null ? $this->name : $name, $this->name);
        return $this;
    }

    /**
     * @param string|null $name
     * @return $this
     */
    public function index($name = null)
    {
        $this->table->index($name === null ? $this->name : $name, $this->name);
        return $this;
    }
}
