<?php

namespace Mohamedelaraby\QuickCrud\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Mohamedelaraby\QuickCrud\Services\StubGenerator;
use Mohamedelaraby\QuickCrud\Contracts\GeneratorInterface;
use Illuminate\Support\Str;
use Mohamedelaraby\QuickCrud\Generators\ControllerGenerator;
use Mohamedelaraby\QuickCrud\Generators\ModelGenerator;
use Mohamedelaraby\QuickCrud\Generators\MigrationGenerator;
use Mohamedelaraby\QuickCrud\Generators\ViewGenerator;
use Mohamedelaraby\QuickCrud\Generators\DataTableGenerator;
use Mohamedelaraby\QuickCrud\Services\RouteGenerator;
use Mohamedelaraby\QuickCrud\Services\SeederGenerator;

class GenerateCrud extends Command
{
    protected $signature = 'generate:crud {model}';
    protected $description = 'Generate CRUD files for a given model';

    public function __construct(
        protected ControllerGenerator $controllerGenerator,
        protected ModelGenerator $modelGenerator,
        protected MigrationGenerator $migrationGenerator,
        protected ViewGenerator $viewGenerator,
        protected DataTableGenerator $dataTableGenerator,
        protected SeederGenerator $seederGenerator,
        protected RouteGenerator $routeGenerator
    ) {
        parent::__construct();

        $this->controllerGenerator = $controllerGenerator;
        $this->modelGenerator = $modelGenerator;
        $this->MigrationGenerator = $migrationGenerator;
        $this->ViewGenerator = $viewGenerator;
        $this->DataTableGenerator = $dataTableGenerator;
        $this->SeederGenerator = $seederGenerator;
        $this->RouteGenerator = $routeGenerator;
    }

    public function handle()
    {
        try {
            $modelName = $this->getModelName();
            $modelVariable = Str::camel($modelName);
            $pluralModel = Str::plural($modelVariable);
            $dataTableName = Str::lower(Str::replace(' ', '', $modelName));

            $formattedToTranslationStyle = strtolower(preg_replace('/([a-z])([A-Z])/', '$1 $2', $modelName));

            $this->generateComponents($modelName, $modelVariable, $pluralModel, $dataTableName, $formattedToTranslationStyle);
            $this->updateSystemFiles($modelName, $pluralModel);

            $this->info("CRUD for {$modelName} generated successfully!");
        } catch (\Exception $e) {
            $this->error($e->getMessage());
        }
    }

    private function getModelName(): string
    {
        $model = $this->argument('model');
        return ucfirst(Str::camel($model));
    }

    private function generateComponents(string $modelName, string $modelVariable, string $pluralModel, string $dataTableName, string $formattedToTranslationStyle): void
    {


        $stubVariables = $this->getStubVariables($modelName, $modelVariable, $pluralModel, $dataTableName, $formattedToTranslationStyle);

        $this->controllerGenerator->generate($modelName, $stubVariables, $dataTableName, $formattedToTranslationStyle);
        $this->modelGenerator->generate($modelName, $stubVariables, $dataTableName, $formattedToTranslationStyle);
        $this->migrationGenerator->generate($pluralModel, $stubVariables, $dataTableName, $formattedToTranslationStyle);
        $this->viewGenerator->generate($modelName, $stubVariables, $dataTableName, $formattedToTranslationStyle);
        $this->dataTableGenerator->generate($modelName, $stubVariables, $dataTableName, $formattedToTranslationStyle);
        $this->seederGenerator->generate($modelName, $modelVariable);
    }

    private function updateSystemFiles(string $modelName, string $pluralModel): void
    {
        $this->routeGenerator->addResourceRoute($pluralModel, $modelName);
        $this->seederGenerator->updateDatabaseSeeder($modelName);
    }

    private function getStubVariables(string $modelName, string $modelVariable, string $pluralModel, string $dataTableName, string $formattedToTranslationStyle): array
    {
        return [
            'ModelName' => $modelName,
            'modelVariable' => $modelVariable,
            'pluralModel' => $pluralModel,
            'dataTableName' => $dataTableName,
            'formattedToTranslationStyle' => $formattedToTranslationStyle,
            'titleUpperCase' => Str::upper(Str::snake($modelName, ' ')),
            'translationKey' => Str::kebab($modelName),
        ];
    }
}
