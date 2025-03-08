<?php

namespace Mohamedelaraby\QuickCrud\Services;

use Exception;
use Illuminate\Support\Facades\File;

class SeederGenerator
{
    public function generate(string $modelName, string $modelVariable): void
    {
        $stub = File::get(__DIR__.'/../../resources/stubs/seeders/seeder.stub');
        $stub = str_replace(
            ['{{ModelName}}', '{{modelVariable}}'],
            [$modelName, $modelVariable],
            $stub
        );

        $path = database_path("seeders/{$modelName}Seeder.php");

        if (File::exists($path)) {
            throw new \Exception("Seeder file already exists: {$path}");
        }

        File::put($path, $stub);
    }

    public function updateDatabaseSeeder(string $modelName): void
    {


// Define the path to the DatabaseSeeder.php file
$path = database_path('seeders/DatabaseSeeder.php');

// Check if the file exists, otherwise throw an exception
if (!File::exists($path)) {
    throw new Exception("DatabaseSeeder.php file not found.");
}

// Read the content of the DatabaseSeeder.php file
$content = File::get($path);

// Define the regex pattern to search for the run() method
$searchPattern = '/public function run\(\): void\s*{(.*?)}/s';

// Check if the run() method exists in the file
if (preg_match($searchPattern, $content, $matches)) {
    // Define the new call statement to be inserted
    $newCall = "\n    \$this->call({$modelName}Seeder::class);";

    // Ensure the call statement is not already present
    if (strpos($matches[1], "\$this->call({$modelName}Seeder::class);") !== false) {
        throw new Exception("Seeder call already exists in DatabaseSeeder.php.");
    }

    // Update the content by inserting the new call immediately after the opening brace
    $updatedContent = preg_replace(
        $searchPattern,
        "public function run(): void \n    {" . $newCall . $matches[1] . "}",
        $content
    );


    // Save the updated content back to the file
    File::put($path, $updatedContent);
} else {
    // Throw an exception if the run() method is not found
    throw new Exception("Method run() not found in DatabaseSeeder.php.");
}
    }
}
