<?php

declare(strict_types=1);

namespace DeveloperSamuel\PhpCodeStats\Utils;

use DeveloperSamuel\PhpCodeStats\Loader\AnalyzerLoader;

final class PathInspector
{
    /**
     * @param string $path
     *
     * @return bool
    */
    public static function isIgnoredPath(string $path): bool
    {
        AnalyzerLoader::loadConfig();

        $parts = explode(DIRECTORY_SEPARATOR, $path);

        return (bool) array_intersect(
            $parts,
            AnalyzerLoader::getIgnoredDirs()
        );
    }
}
