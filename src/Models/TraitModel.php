<?php

namespace Clive0417\ModelGenerator\Models;

class TraitModel
{
    protected $trait_name;


    public function __construct($trait_name)
    {
        $this->trait_name  = $trait_name;
    }

    public function toLine()
    {
        return "\t".sprintf('use %s;',$this->trait_name);
    }
}
