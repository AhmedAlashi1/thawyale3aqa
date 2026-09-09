<?php

namespace App\Support;

use Illuminate\Support\Str;

class ViteAssets
{
    /**
     * Render script/link tags from a Vite manifest (Laravel 8 has no @vite).
     *
     * @param  string|array  $entry
     * @param  string  $buildDirectory
     * @return string
     */
    public static function render($entry, $buildDirectory = 'build')
    {
        $buildDirectory = trim($buildDirectory, '/');
        $manifestPath = public_path($buildDirectory.'/manifest.json');

        if (! is_file($manifestPath)) {
            return '';
        }

        $manifest = json_decode(file_get_contents($manifestPath), true) ?: [];
        $html = '';

        foreach ((array) $entry as $name) {
            if (! isset($manifest[$name]['file'])) {
                continue;
            }

            $chunk = $manifest[$name];

            foreach ($chunk['css'] ?? [] as $css) {
                $html .= '<link rel="stylesheet" href="'.e(asset($buildDirectory.'/'.$css)).'">';
            }

            $url = asset($buildDirectory.'/'.$chunk['file']);

            if (static::isStyle($name, $chunk['file'])) {
                $html .= '<link rel="stylesheet" href="'.e($url).'">';
            } else {
                $html .= '<script type="module" src="'.e($url).'"></script>';
            }
        }

        return $html;
    }

    /**
     * @param  string  $entry
     * @param  string  $file
     * @return bool
     */
    protected static function isStyle($entry, $file)
    {
        return Str::endsWith($file, '.css')
            || Str::endsWith($entry, ['.css', '.scss', '.sass']);
    }
}
