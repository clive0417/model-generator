<?php

namespace Clive0417\ModelGenerator\Models;

use Clive0417\ModelGenerator\Formats\EntityCreatorModelFormat;

class UseModel
{
    protected $use_name;


    public function __construct($use_name)
    {
        $this->use_name  = $use_name;
    }

    public function toLine()
    {
        return sprintf('use %s\%s;',EntityCreatorModelFormat::getUsePath($this->use_name),$this->use_name);
    }
}
