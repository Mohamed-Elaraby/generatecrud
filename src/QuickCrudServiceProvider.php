<?php

namespace Mohamedelaraby\QuickCrud;

use Illuminate\Support\ServiceProvider;
use Mohamedelaraby\QuickCrud\Console\Commands\GenerateCrud;
use Mohamedelaraby\QuickCrud\Contracts\GeneratorInterface;
use Mohamedelaraby\QuickCrud\Generators\ControllerGenerator;
use Mohamedelaraby\QuickCrud\Generators\ModelGenerator;
use Mohamedelaraby\QuickCrud\Services\StubGenerator;

class QuickCrudServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind(
            \Mohamedelaraby\QuickCrud\Contracts\GeneratorInterface::class,
            \Mohamedelaraby\QuickCrud\Generators\ControllerGenerator::class
        );

        $this->app->singleton(
            \Mohamedelaraby\QuickCrud\Services\StubGenerator::class
        );

        $this->app->bind(
            \Mohamedelaraby\QuickCrud\Generators\ControllerGenerator::class,
            function ($app) {
                return new \Mohamedelaraby\QuickCrud\Generators\ControllerGenerator(
                    $app->make(\Mohamedelaraby\QuickCrud\Services\StubGenerator::class)
                );
            }
        );
    }

    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                GenerateCrud::class,
            ]);
        }
    }
}