<?php

namespace Clive0417\ModelGenerator\Facades;

use Illuminate\Support\Facades\Facade;

class ModelGenerator extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'model-generator';
    }
}
