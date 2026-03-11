<?php

declare(strict_types=1);

namespace DeveloperSamuel\PhpCodeStats\Counter;

use DeveloperSamuel\PhpCodeStats\{
    Abstract\AbstractFileCounter,
    Value\FileMetrics
};

class LineCounter extends AbstractFileCounter
{
    /**
     * @return void
    */
    public function analyze(): void {
        $this->processFile(
            static fn(FileMetrics $metrics): int => $metrics->lines,
            'lines'
        );
    }
}
