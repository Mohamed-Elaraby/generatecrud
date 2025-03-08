<?php

namespace Mohamedelaraby\QuickCrud\Contracts;

interface GeneratorInterface
{
    public function generate(string $name, array $variables, string $dataTableName, string $formattedToTranslationStyle, string $databaseSchemaTableName): void;
    public function getStubPath(): string;
    public function getTargetPath(string $name): string;
}
