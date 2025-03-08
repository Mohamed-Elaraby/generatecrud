<?php

namespace Mohamedelaraby\QuickCrud\Generators;

use Mohamedelaraby\QuickCrud\Contracts\GeneratorInterface;
use Mohamedelaraby\QuickCrud\Services\StubGenerator;
use Illuminate\Support\Facades\File;

class DataTableGenerator implements GeneratorInterface
{
    public function __construct(protected StubGenerator $stubGenerator) {}

    public function generate(string $name, array $variables, string $dataTableName, string $formattedToTranslationStyle, string $databaseSchemaTableName): void
    {
        $stub = $this->stubGenerator->getStubContent(
            $this->getStubPath(),
            $variables
        );

        $path = $this->getTargetPath($name);

        if (File::exists($path)) {
            throw new \Exception("DataTable file already exists: {$path}");
        }

        File::ensureDirectoryExists(dirname($path));
        File::put($path, $stub);
    }

    public function getStubPath(): string
    {
        return 'datatables/datatable.stub';
    }

    public function getTargetPath(string $name): string
    {
        return base_path("app/DataTables/{$name}DataTable.php");
    }
}
