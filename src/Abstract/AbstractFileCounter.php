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

        return new FileMetrics(
            files: 1,
            rows: substr_count($content, "\n") + (strlen($content) > 0 ? 1 : 0),
            chars: strlen($content),
            content: $content
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
}
