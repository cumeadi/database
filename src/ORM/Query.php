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

namespace Opis\Database\ORM;

use Opis\Database\SQL\BaseStatement;
use Opis\Database\SQL\HavingStatement;
use Opis\Database\SQL\SQLStatement;

class Query extends BaseStatement
{
    use SelectTrait {
        select as protected;
    }
    use SoftDeletesTrait;
    use LoaderTrait;

    /** @var HavingStatement */
    protected $have;

    /**
     * Query constructor.
     * @param SQLStatement|null $statement
     */
    public function __construct(SQLStatement $statement = null)
    {
        parent::__construct($statement);
        $this->have = new HavingStatement($this->sql);
    }

    /**
     * @return HavingStatement
     */
    protected function getHavingStatement(): HavingStatement
    {
        return $this->have;
    }
}