<?php

namespace Mohamedelaraby\QuickCrud\Generators;

use Mohamedelaraby\QuickCrud\Contracts\GeneratorInterface;
use Mohamedelaraby\QuickCrud\Services\StubGenerator;
use Illuminate\Support\Facades\File;

class ModelGenerator implements GeneratorInterface
{
    public function __construct(protected StubGenerator $stubGenerator) {}

    public function generate(string $name, array $variables, string $dataTableName, string $formattedToTranslationStyle, string $databaseSchemaTableName): void
    {
        $stub = $this->stubGenerator->getStubContent($this->getStubPath(), $variables);
        $path = $this->getTargetPath($name);

        File::ensureDirectoryExists(dirname($path));
        File::put($path, $stub);
    }

    public function getStubPath(): string
    {
        return 'models/model.stub';
    }

    public function getTargetPath(string $name): string
    {
        return base_path("app/Models/{$name}.php");
    }
}
