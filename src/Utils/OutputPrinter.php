<?php

declare(strict_types=1);

namespace DeveloperSamuel\PhpCodeStats\Utils;

final class OutputPrinter
{
    /**
     * @param string $file
     * @param int $count
     * @param string $unit
     *
     * @return void
    */
    public static function printFileProgress(string $file, int $count, string $unit = 'rows'): void
    {
        echo "Processed file: {$file} ({$count} {$unit})\n";
    }

    /**
     * @param int $total
     * @param string $unit
     *
     * @return void
    */
    public static function printTotal(int $total, string $unit = 'rows'): void
    {
        echo "\nTotal {$unit}: " . number_format($total, 0, '.', ' ');
    }
}
