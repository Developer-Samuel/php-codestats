<?php

declare(strict_types=1);

namespace DeveloperSamuel\PhpCodeStats\Scanner;

use DeveloperSamuel\PhpCodeStats\Utils\PathFilter;

final class DirectoryScanner
{
    /**
     * @param string $dir
     *
     * @return \RecursiveIteratorIterator<\RecursiveDirectoryIterator>
    */
    public static function createRecursiveIterator(string $dir): \RecursiveIteratorIterator
    {
        return new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($dir, \FilesystemIterator::SKIP_DOTS)
        );
    }

    /**
     * @param \RecursiveIteratorIterator<\RecursiveDirectoryIterator> $iterator
     *
     * @return iterable<string>
    */
    public static function filterPhpFiles(\RecursiveIteratorIterator $iterator): iterable
    {
        foreach ($iterator as $file) {
            if (!$file instanceof \SplFileInfo) {
                continue;
            }
            
            $filePath = $file->getPathname();

            if (PathFilter::isPhpFile($filePath)) {
                yield $filePath;
            }
        }
    }
}
