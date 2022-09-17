<?php

namespace Clive0417\ModelGenerator\Models;

use Clive0417\ModelGenerator\Formats\EntityCreatorModelFormat;

class FillableModel
{
    protected $fillable = [];

    public function addFillable($column_name)
    {
        $this->fillable[] = $column_name;
    }

    public function toLine()
    {
        $full_line = '';
        if (!empty($this->fillable)) {
            $full_line = EntityCreatorModelFormat::getIndent().'protected $fillable = ['.PHP_EOL;
            foreach ($this->fillable as $date_column_name) {
                $full_line = $full_line.EntityCreatorModelFormat::getIndent().EntityCreatorModelFormat::getIndent()."'".$date_column_name."'".",".PHP_EOL;
            }
            $full_line = $full_line.EntityCreatorModelFormat::getIndent().'];';
        }
        return $full_line;
    }
}
