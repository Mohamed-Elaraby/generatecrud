<?php

namespace Mohamedelaraby\QuickCrud\Generators;

use Mohamedelaraby\QuickCrud\Contracts\GeneratorInterface;
use Mohamedelaraby\QuickCrud\Services\StubGenerator;
use Illuminate\Support\Facades\File;

class ViewGenerator implements GeneratorInterface
{
    public function __construct(protected StubGenerator $stubGenerator) {}

    public function generate(string $name, array $variables, string $dataTableName, string $formattedToTranslationStyle, string $databaseSchemaTableName): void
    {
        $views = ['index', 'create', 'edit'];
        $pluralName = $variables['pluralModel'];
        $viewPath = resource_path("views/admin/{$pluralName}");

        File::ensureDirectoryExists($viewPath);

        foreach ($views as $view) {
            $stub = $this->stubGenerator->getStubContent(
                "views/{$view}.stub",
                $variables
            );

            $path = "{$viewPath}/{$view}.blade.php";

            if (File::exists($path)) {
                throw new \Exception("View file already exists: {$path}");
            }

            File::put($path, $stub);
        }
    }

    public function getStubPath(): string
    {
        return 'views/'; // Not used directly in this implementation
    }

    public function getTargetPath(string $name): string
    {
        return resource_path("views/admin/"); // Not used directly
    }
}
