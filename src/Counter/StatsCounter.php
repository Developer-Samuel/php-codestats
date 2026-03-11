<?php

declare(strict_types=1);

namespace DeveloperSamuel\PhpCodeStats\Counter;

use DeveloperSamuel\PhpCodeStats\{
    Abstract\AbstractFileCounter,
    Utils\OutputPrinter
};

class StatsCounter extends AbstractFileCounter
{
    /**
     * @return void
    */
    public function analyze(): void
    {
        $filesCount = 0;
        $rowsCount  = 0;
        $charsCount = 0;

        foreach ($this->getTargetFiles() as $file) {
            if (!is_readable($file)) {
                continue;
            }

            $metrics = $this->getFileData($file);

            $filesCount++;
            $rowsCount  += $metrics->rows;
            $charsCount += $metrics->chars;

            OutputPrinter::printFileProgress($file, 1, 'analyzed');
        }

        $this->printFinalReport($filesCount, $rowsCount, $charsCount);
    }

    /**
     * @param int $files
     * @param int $rows
     * @param int $chars
     *
     * @return void
    */
    private function printFinalReport(int $files, int $rows, int $chars): void
    {
        $sep = str_repeat('-', 40);
        echo "\n{$sep}\n🚀 PHP Code Stats\n{$sep}";

        OutputPrinter::printTotal($files, 'files');
        OutputPrinter::printTotal($rows, 'rows');
        OutputPrinter::printTotal($chars, 'characters');

        echo PHP_EOL . $sep . PHP_EOL;
    }
}
