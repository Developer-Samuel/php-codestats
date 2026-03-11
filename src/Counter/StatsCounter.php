<?php

declare(strict_types=1);

namespace DeveloperSamuel\PhpCodeStats\Counter;

use DeveloperSamuel\PhpCodeStats\{
    Abstract\AbstractFileCounter,
    Utils\OutputPrinter,
    Value\FileMetrics
};

/**
 * @phpstan-type TotalsShape array{
 *     files: int,
 *     lines: int,
 *     chars: int,
 *     empty: int,
 *     comments: int
 * }
*/
class StatsCounter extends AbstractFileCounter
{
    /**
     * @return void
     */
    public function analyze(): void
    {
        $totals = $this->initializeTotals();
        $extensionsCount = [];

        foreach ($this->getTargetFiles() as $file) {
            if (!is_readable($file)) {
                continue;
            }

            $metrics = $this->getFileData($file);

            $totals = $this->aggregateTotals($totals, $metrics);
            $extensionsCount = $this->trackExtension($extensionsCount, $metrics->extension);

            OutputPrinter::printFileProgress($file, 1, 'analyzed');
        }

        $this->printAdvancedReport($totals, $extensionsCount);
    }

    /**
     * @return TotalsShape
    */
    private function initializeTotals(): array
    {
        return [
            'files' => 0,
            'lines' => 0,
            'chars' => 0,
            'empty' => 0,
            'comments' => 0
        ];
    }

    /**
     * @param TotalsShape $totals
     * @param FileMetrics $metrics
     *
     * @return TotalsShape
    */
    private function aggregateTotals(array $totals, FileMetrics $metrics): array
    {
        $totals['files']++;
        $totals['lines']    += $metrics->lines;
        $totals['chars']    += $metrics->chars;
        $totals['empty']    += $metrics->emptyLines;
        $totals['comments'] += $metrics->commentLines;

        return $totals;
    }

    /**
     * @param array<string, int> $extensionsCount
     * @param string $extension
     *
     * @return array<string, int>
    */
    private function trackExtension(array $extensionsCount, string $extension): array
    {
        $ext = strtolower($extension);
        $extensionsCount[$ext] = ($extensionsCount[$ext] ?? 0) + 1;

        return $extensionsCount;
    }

    /**
     * @param TotalsShape $totals
     * @param array<string, int> $exts
     *
     * @return void
    */
    private function printAdvancedReport(array $totals, array $exts): void
    {
        $width = 50;
        $sep = str_repeat('-', $width + 12);

        $this->printHeader($sep);
        $this->printMainMetrics($totals, $width);
        $this->printDetailedMetrics($totals, $width, $sep);
        $this->printFileDiscovery($exts, $width, $sep);
        $this->printFooter($sep);
    }

    /**
     * @param string $sep
     *
     * @return void
    */
    private function printHeader(string $sep): void
    {
        echo "\n\n{$sep}\n";
        echo "📊  CODE STATS SUMMARY\n";
        echo $sep . PHP_EOL;
    }

    /**
     * @param TotalsShape $totals
     * @param int $width
     *
     * @return void
    */
    private function printMainMetrics(array $totals, int $width): void
    {
        OutputPrinter::printFormattedRow("Total Files:", $totals['files'], $width);
        OutputPrinter::printFormattedRow("Total Lines:", $totals['lines'], $width);
        OutputPrinter::printFormattedRow("Total Chars:", $totals['chars'], $width);
    }

    /**
     * @param TotalsShape $totals
     * @param int $width
     * @param string $sep
     *
     * @return void
    */
    private function printDetailedMetrics(array $totals, int $width, string $sep): void
    {
        $codeLines = $totals['lines'] - $totals['empty'] - $totals['comments'];

        echo $sep . PHP_EOL;
        OutputPrinter::printFormattedRow("Code Lines:", $codeLines, $width);
        OutputPrinter::printFormattedRow("Empty Lines:", $totals['empty'], $width);
        OutputPrinter::printFormattedRow("Comment Lines (//, /*, */, #):", $totals['comments'], $width);
    }

    /**
     * @param array<string, int> $exts
     * @param int $width
     * @param string $sep
     *
     * @return void
    */
    private function printFileDiscovery(array $exts, int $width, string $sep): void
    {
        echo $sep . PHP_EOL;
        echo "📂  FILE TYPES DISCOVERY\n";
        echo $sep . PHP_EOL;

        ksort($exts);
        foreach ($exts as $ext => $count) {
            $label = strtoupper($ext === '' ? 'no-ext' : $ext);
            OutputPrinter::printFormattedRow($label, $count, $width);
        }
    }

    /**
     * @param string $sep
     *
     * @return void
    */
    private function printFooter(string $sep): void
    {
        echo $sep . PHP_EOL;
        echo "🚀 Analysis complete.\n";
    }
}
