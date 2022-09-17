<?php

namespace Clive0417\ModelGenerator\Models;

use Clive0417\ModelGenerator\Formats\EntityCreatorModelFormat;

class EntityPathModel
{
    protected $entity_path;

    public function __construct(string $table_name)
    {
        $this->entity_path =  sprintf('%s%s/Entities/',config('model-generator.entity_root_path'),EntityCreatorModelFormat::getTableGroupName($table_name),) ;
    }

    public function toLine()
    {
        return $this->entity_path;
    }

}
