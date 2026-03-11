<?php

declare(strict_types=1);

/**
 * @param int $levels
 *
 * @return string
 *
 * @throws \RuntimeException
*/
function findAutoload(int $levels = 5): string
{
    $dir = __DIR__;
    for ($i = 0; $i <= $levels; $i++) {
        $autoloadPath = $dir . str_repeat('/..', $i) . '/vendor/autoload.php';
        $realPath = realpath($autoloadPath);
        if ($realPath && is_file($realPath)) {
            return $realPath;
        }
    }

    throw new \RuntimeException('autoload.php not found in any of the expected directories.');
}
