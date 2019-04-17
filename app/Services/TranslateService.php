<?php

namespace App\Services;

use Barryvdh\TranslationManager\Models\Translation;
use Illuminate\Support\Facades\Log;

class TranslateService
{
    public function export()
    {
        $toExport = [];

        $words = Translation::all();
        foreach ($words as $word) {
            $toExport[$word->locale][$word->key] = $word->value ?: $word->key;
        }

        foreach ($toExport as $locale => $keys) {
                $directory = resource_path(
                    "assets" .
                    DIRECTORY_SEPARATOR .
                    "js" .
                    DIRECTORY_SEPARATOR .
                    "lang" .
                    DIRECTORY_SEPARATOR);

                if (!is_dir($directory)) {
                    if (!mkdir($directory, $mode = 0755, true) && !is_dir($directory)) {
                        throw new \RuntimeException(sprintf('Directory "%s" was not created', $directory));
                    }
                }

                $content = 'export default ' .json_encode($keys);
            file_put_contents(
                $directory . $locale . ".js",
                $content
            );
        }

        exec("cd /var/www && npm run prod", $output, $status);
    }
}