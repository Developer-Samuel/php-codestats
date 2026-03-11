<?php

declare(strict_types=1);

namespace DeveloperSamuel\PhpCodeStats;

final class Installer
{
    /**
     * @return void
    */
    public static function copyConfig(): void
    {
        $root = getcwd();

        $target = $root . '/codestats-analyzer.xml';
        if (file_exists($target)) {
            return;
        }

        $source = dirname(__DIR__) . '/config/analyzer.xml';
        if (!is_file($source)) {
            throw new \RuntimeException('Source config not found at ' . $source);
        }

        if (!copy($source, $target)) {
            throw new \RuntimeException('Failed to copy analyzer.xml to ' . $target);
        }

        echo sprintf('✅ codestats-analyzer.xml created in project root: %s%s', $target, PHP_EOL);
    }
}
