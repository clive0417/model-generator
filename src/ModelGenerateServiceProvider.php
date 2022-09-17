<?php

namespace Clive0417\ModelGenerator;

use Clive0417\ModelGenerator\Commands\ModelGenerateCommand;

class ModelGenerateServiceProvider extends \Illuminate\Support\ServiceProvider
{
    const CONFIG_PATH = __DIR__ . '/../config/model-generator.php';

    public function boot()
    {
        $this->publishes([
            self::CONFIG_PATH => config_path('model-generator.php'),
        ], 'config');
        $this->commands([
            //產生generate 的 model
            ModelGenerateCommand::class
        ]);
    }

    public function register()
    {
        $this->mergeConfigFrom(
            self::CONFIG_PATH,
            'model-generator'
        );

        $this->app->bind('model-generator', function () {
            return new ModelGenerator();
        });
    }
}
