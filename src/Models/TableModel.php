<?php

namespace Clive0417\ModelGenerator\Models;

use Clive0417\ModelGenerator\Formats\EntityCreatorModelFormat;

class TableModel
{
    protected $table_name;


    public function __construct($table_name)
    {
        $this->table_name  = $table_name;
    }

    public function toLine()
    {
        return "\t".'protected $table = '."'".$this->table_name."';";
    }
}
