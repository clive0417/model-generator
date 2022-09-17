<?php

namespace Clive0417\ModelGenerator\Models;

use Clive0417\ModelGenerator\Formats\EntityCreatorModelFormat;

class NameSpaceModel
{

    protected $name_space_path;

    public function __construct($table_name)
    {
        $this->name_space_path = 'namespace '.str_replace('/','\\',sprintf('%s%s/Entities;',config('model-generator.entity_namespace_root_path'),EntityCreatorModelFormat::getTableGroupName($table_name)));
    }

    public function toLine()
    {
        return $this->name_space_path;
    }
}
