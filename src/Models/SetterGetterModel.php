<?php

namespace Clive0417\ModelGenerator\Models;


use Clive0417\ModelGenerator\Formats\EntityCreatorModelFormat;
use Doctrine\DBAL\Schema\Column;

class SetterGetterModel
{
    protected $Column;

    public function __construct(Column $Column)
    {
        $this->Column = $Column;
    }

    public function toLine()
    {
        $setter = '';
        $getter = '';

        $setter = EntityCreatorModelFormat::generateSetFunction($this->Column);
        $getter = EntityCreatorModelFormat::generateGetFunction($this->Column);

        return $setter.PHP_EOL.$getter.PHP_EOL;
    }
}
