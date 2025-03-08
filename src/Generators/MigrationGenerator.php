<?php

namespace Mohamedelaraby\QuickCrud\Generators;

use Mohamedelaraby\QuickCrud\Contracts\GeneratorInterface;
use Mohamedelaraby\QuickCrud\Services\StubGenerator;
use Illuminate\Support\Facades\File;

class MigrationGenerator implements GeneratorInterface
{
    public function __construct(protected StubGenerator $stubGenerator) {}

    public function generate(string $name, array $variables, string $dataTableName, string $formattedToTranslationStyle, string $databaseSchemaTableName): void
    {
        $stub = $this->stubGenerator->getStubContent(
            $this->getStubPath(),
            $variables
        );

        $fileName = date('Y_m_d_His')."_create_{$databaseSchemaTableName}_table.php";
        $path = database_path("migrations/{$fileName}");

        if (File::glob(database_path("migrations/*_create_{$databaseSchemaTableName}_table.php"))) {
            throw new \Exception("Migration for {$databaseSchemaTableName} table already exists");
        }

        File::put($path, $stub);
    }

    public function getStubPath(): string
    {
        return 'migrations/migration.stub';
    }

    public function getTargetPath(string $databaseSchemaTableName): string
    {
        return database_path("migrations/"); // Not used directly in this case
    }
}
