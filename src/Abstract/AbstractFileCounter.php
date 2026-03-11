<?php

declare(strict_types=1);

namespace DeveloperSamuel\PhpCodeStats\Abstract;

use DeveloperSamuel\PhpCodeStats\{
    Loader\AnalyzerLoader,
    Utils\FileFinder,
    Utils\OutputPrinter,
    Value\FileMetrics
};

abstract class AbstractFileCounter
{
    protected string $directory;

    /** @var string[] */
    protected array $extensions;

    /**
     * @param string $directory
    */
    public final function __construct(string $directory)
    {
        AnalyzerLoader::loadConfig();

        $this->directory = $directory;
        $this->extensions = AnalyzerLoader::getFileExtensions();
    }

    /**
     * @return void
    */
    abstract public function analyze(): void;

    /**
     * @param callable(FileMetrics): int $logic
     * @param string $unit
     *
     * @return void
    */
    protected function processFile(callable $logic, string $unit): void
    {
        $total = 0;

        foreach ($this->getTargetFiles() as $file) {
            if (!is_readable($file)) {
                continue;
            }

            $data = $this->getFileData($file);
            $result = $logic($data);

            if (is_int($result)) {
                $total += $result;
                OutputPrinter::printFileProgress($file, $result, $unit);
            }
        }

        if ($total > 0 || $unit !== 'analyzed') {
            OutputPrinter::printTotal($total, $unit);
        }
    }

    /**
     * @param string $file
     *
     * @return FileMetrics
    */
    protected function getFileData(string $file): FileMetrics
    {
        $content = (string) file_get_contents($file);
        $lines = explode("\n", $content);

        return new FileMetrics(
            files: 1,
            lines: count($lines),
            chars: strlen($content),
            commentLines: $this->countCommentLines($lines),
            emptyLines: $this->countEmptyLines($lines),
            content: $content,
            extension: pathinfo($file, PATHINFO_EXTENSION)
        );
    }

    /**
     * @return iterable<string>
    */
    protected function getTargetFiles(): iterable
    {
        return FileFinder::getFiles(
            $this->directory,
            $this->extensions
        );
    }

    /**
     * @param string[] $lines
     *
     * @return int
    */
    private function countEmptyLines(array $lines): int
    {
        $count = 0;
        foreach ($lines as $line) {
            if (trim($line) === '') {
                $count++;
            }
        }

        return $count;
    }

    /**
     * @param string[] $lines
     *
     * @return int
    */
    private function countCommentLines(array $lines): int
    {
        $count = 0;
        foreach ($lines as $line) {
            if ($this->isComment(trim($line))) {
                $count++;
            }
        }

        return $count;
    }

    /**
     * @param string $line
     *
     * @return bool
    */
    private function isComment(string $line): bool
    {
        if ($line === '') {
            return false;
        }

        $prefixes = ['//', '/*', '*/', '#'];
        foreach ($prefixes as $prefix) {
            if (str_starts_with($line, $prefix)) {
                return true;
            }
        }

        return false;
    }
}
