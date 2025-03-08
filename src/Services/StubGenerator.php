<?php

namespace Mohamedelaraby\QuickCrud\Services;
use Illuminate\Support\Facades\File;


class StubGenerator
{
    public function getStubContent(string $stubPath, array $variables): string
    {
        $fullPath = $this->getFullStubPath($stubPath);

        if (!File::exists($fullPath)) {
            throw new \Exception("Stub file not found: {$fullPath}");
        }
        $content = File::get($fullPath);
        foreach ($variables as $key => $value) {
            $content = str_replace("{{{$key}}}", $value, $content);
        }

        return $content;
    }

    private function getFullStubPath(string $stubPath): string
    {
        return __DIR__ . "/../../resources/stubs/{$stubPath}";
    }
}
