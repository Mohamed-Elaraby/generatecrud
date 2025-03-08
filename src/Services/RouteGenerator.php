<?php

namespace Mohamedelaraby\QuickCrud\Services;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class RouteGenerator
{
    public function addResourceRoute(string $pluralModel, string $modelName): void
    {
        $path = base_path('routes/web.php');
        $content = File::get($path);

        // Check if the route already exists
        if (Str::contains($content, "Route::resource('{$pluralModel}'")) {
            throw new \Exception("Route for {$pluralModel} already exists!");
        }

        // Define the required `use` statement
        $useStatement = "use App\Http\Controllers\Admin\\{$modelName}Controller;";

        // Search for `<?php` and add the `use` statement after it if not already added
        if (!Str::contains($content, $useStatement)) {
            $content = preg_replace('/<\?php\s*/', "<?php\n\n$useStatement\n", $content, 1);
        }

        // Define the new route in the required format
        $newRoute = "\n    Route::resource('{$pluralModel}', {$modelName}Controller::class);";

        // Search for Route::prefix('admin')
        if (preg_match("/Route::prefix\('admin'\)\s*->name\('admin.'\)\s*->middleware\('auth'\)\s*->group\(function\s*\(\)\s*\{/", $content, $matches)) {
            // Insert the new route inside the existing group
            $updatedContent = preg_replace(
                "/(Route::prefix\('admin'\)\s*->name\('admin.'\)\s*->middleware\('auth'\)\s*->group\(function\s*\(\)\s*\{)/",
                "$1$newRoute",
                $content
            );
        } elseif (Str::contains($content, "Route::prefix('admin')")) {
            // If `Route::prefix('admin')` exists but does not contain `->group`
            $updatedContent = preg_replace(
                "/(Route::prefix\('admin'\')\s*;)/",
                "$1\n->name('admin.')\n->middleware('auth')\n->group(function (){{$newRoute}\n    });",
                $content
            );
        } else {
            // Create a new group if it does not exist
            $updatedContent = $content . "\n\nRoute::prefix('admin')\n    ->name('admin.')\n    ->middleware('auth')\n    ->group(function (){{$newRoute}\n    });";
        }

        // Save the modifications to the file
        File::put($path, $updatedContent);
    }

}
