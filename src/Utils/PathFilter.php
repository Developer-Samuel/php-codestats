<?php

declare(strict_types=1);

namespace DeveloperSamuel\PhpCodeStats\Utils;

final class PathFilter
{
    /**
     * @param string $path
     * 
     * @return bool
    */
    public static function isPhpFile(string $path): bool
    {
        return str_ends_with($path, '.php');
    }
}
