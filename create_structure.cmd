@echo off

:: إنشاء مجلدات src
mkdir src\Console\Commands
mkdir src\Contracts
mkdir src\Generators
mkdir src\Services

:: إنشاء مجلدات resources
mkdir resources\stubs\controllers
mkdir resources\stubs\models
mkdir resources\stubs\migrations
mkdir resources\stubs\views
mkdir resources\stubs\datatables
mkdir resources\stubs\seeders

:: إنشاء مجلدات tests
mkdir tests\Generators
mkdir tests\Services

:: إنشاء الملفات الرئيسية
echo. > composer.json
echo. > README.md

:: إنشاء ملفات src
echo. > src\QuickCrudServiceProvider.php
echo. > src\Console\Commands\GenerateCrud.php
echo. > src\Contracts\GeneratorInterface.php

:: إنشاء ملفات Generators
echo. > src\Generators\ControllerGenerator.php
echo. > src\Generators\ModelGenerator.php
echo. > src\Generators\MigrationGenerator.php
echo. > src\Generators\ViewGenerator.php
echo. > src\Generators\DataTableGenerator.php

:: إنشاء ملفات Services
echo. > src\Services\StubGenerator.php
echo. > src\Services\RouteGenerator.php
echo. > src\Services\SeederGenerator.php

:: إنشاء ملفات stubs
echo. > resources\stubs\controllers\controller.stub
echo. > resources\stubs\models\model.stub
echo. > resources\stubs\migrations\migration.stub
echo. > resources\stubs\views\index.stub
echo. > resources\stubs\views\create.stub
echo. > resources\stubs\views\edit.stub
echo. > resources\stubs\datatables\datatable.stub
echo. > resources\stubs\seeders\seeder.stub

:: إنشاء ملفات tests
echo. > tests\Generators\ControllerGeneratorTest.php
echo. > tests\Generators\ModelGeneratorTest.php
echo. > tests\Services\RouteGeneratorTest.php

echo تم إنشاء الهيكل بنجاح!