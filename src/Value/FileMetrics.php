<?php

declare(strict_types=1);

namespace DeveloperSamuel\PhpCodeStats\Value;

final readonly class FileMetrics
{
    /**
     * @param int $files
     * @param int $rows
     * @param int $chars
     * @param string $content
    */
    public function __construct(
        public int $files,
        public int $rows,
        public int $chars,
        public string $content
    ) {}
}
