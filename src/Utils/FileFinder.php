<?php

declare(strict_types=1);

namespace DeveloperSamuel\PhpCodeStats\Utils;

use Generator;

use DeveloperSamuel\PhpCodeStats\Scanner\DirectoryScanner;

final readonly class FileFinder
{
    /**
     * @param string $directory
     * @param string[] $extensions
     *
     * @return Generator<string>
    */
    public static function getFiles(string $directory, array $extensions): Generator
    {
        $iterator = DirectoryScanner::createRecursiveIterator($directory);

        foreach ($iterator as $file) {
            if (!$file instanceof \SplFileInfo) {
                continue;
            }

            if (!$file->isFile()) continue;

            $path = $file->getRealPath();
            if ($path === false) continue;

            if (!in_array($file->getExtension(), $extensions, true)) {
                continue;
            }

            if (PathInspector::isIgnoredPath($path)) {
                continue;
            }

            yield $path;
        }
    }
}
