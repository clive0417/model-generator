<?php

namespace Clive0417\ModelGenerator\Tests;

use Clive0417\ModelGenerator\Facades\ModelGenerator;
use Clive0417\ModelGenerator\ServiceProvider;
use Orchestra\Testbench\TestCase;

class ModelGeneratorTest extends TestCase
{
    protected function getPackageProviders($app)
    {
        return [ServiceProvider::class];
    }

    protected function getPackageAliases($app)
    {
        return [
            'model-generator' => ModelGenerator::class,
        ];
    }

    public function testExample()
    {
        $this->assertEquals(1, 1);
    }
}
