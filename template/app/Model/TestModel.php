<?php declare(strict_types=1);
/**
 * DuckPhp
 * From this time, you never be alone~
 */

namespace LazyToChange\Model;

use LazyToChange\System\BaseModel;
use LazyToChange\System\Helper\ModelHelper as M;

class TestModel extends BaseModel
{
    public function foo()
    {
        return DATE(DATE_ATOM);
    }
}
