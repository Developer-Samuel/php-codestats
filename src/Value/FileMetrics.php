<?php

declare(strict_types=1);

namespace DeveloperSamuel\PhpCodeStats\Value;

final readonly class FileMetrics
{
    /**
     * @param int $files
     * @param int $lines
     * @param int $chars
     * @param int $emptyLines
     * @param int $commentLines
     * @param string $content
     * @param string $extension
    */
    public function __construct(
        public int $files,
        public int $lines,
        public int $chars,
        public int $emptyLines,
        public int $commentLines,
        public string $content,
        public string $extension
    ) {}
}
