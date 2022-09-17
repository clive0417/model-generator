<?php

namespace Clive0417\ModelGenerator\Models;

use Clive0417\ModelGenerator\Formats\EntityCreatorModelFormat;

class EntityNameModel
{
    protected $entity_name;

    public function __construct(string $table_name)
    {
        $this->entity_name =  EntityCreatorModelFormat::getEntityName($table_name);
    }

    public function toLine()
    {
        return $this->entity_name;
    }

}
