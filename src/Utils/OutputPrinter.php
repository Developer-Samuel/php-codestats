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
    public static function printFileProgress(string $file, int $count, string $unit): void
    {
        echo "Processed file: {$file} ({$count} {$unit})\n";
    }

    /**
     * @param int $total
     * @param string $unit
     *
     * @return void
    */
    public static function printTotal(int $total, string $unit): void
    {
        echo "\nTotal {$unit}: " . self::format($total) . PHP_EOL;
    }

    /**
     * @param string $label
     * @param int $value
     * @param int $width
     *
     * @return void
    */
    public static function printFormattedRow(string $label, int $value, int $width): void
    {
        printf("%-{$width}s %10s\n", $label, self::format($value));
    }

    /**
     * @param int $value
     *
     * @return string
    */
    public static function format(int $value): string
    {
        return number_format($value, 0, '.', ' ');
    }
}
