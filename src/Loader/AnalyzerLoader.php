<?php

declare(strict_types=1);

namespace DeveloperSamuel\PhpCodeStats\Loader;

final class AnalyzerLoader
{
    /** @var array{file_extensions: string[], ignored_dirs: string[]} */
    private static array $config = [
        'file_extensions' => [],
        'ignored_dirs'    => []
    ];

    /**
     * @param string|null $customPath
     *
     * @return void
     *
     * @throws \RuntimeException
    */
    public static function loadConfig(?string $customPath = null): void
    {
        $filePath = $customPath ?? getcwd() . '/codestats-analyzer.xml';
        if (!is_file($filePath)) {
            throw new \RuntimeException('Analyzer config XML not found at ' . $filePath);
        }

        $xml = simplexml_load_file($filePath);
        if (!$xml instanceof \SimpleXMLElement) {
            throw new \RuntimeException('Invalid XML config: ' . $filePath);
        }

        self::$config['file_extensions'] = self::parseNodes($xml->file_extensions->ext ?? []);
        self::$config['ignored_dirs'] = self::parseNodes($xml->ignored_dirs->dir ?? []);

        self::validateConfig();
    }

    /**
     * @return string[]
     */
    public static function getFileExtensions(): array
    {
        return self::$config['file_extensions'] ?? [];
    }

    /**
     * @return string[]
     */
    public static function getIgnoredDirs(): array
    {
        return self::$config['ignored_dirs'] ?? [];
    }

    /**
     * @return void
     */
    private static function validateConfig(): void
    {
        self::$config['file_extensions'] = self::sanitizeArray(self::$config['file_extensions']);
        self::$config['ignored_dirs'] = self::sanitizeArray(self::$config['ignored_dirs']);
    }

    /**
     * @param iterable<\SimpleXMLElement|string>$nodes
     * @return string[]
     */
    private static function parseNodes(iterable $nodes): array
    {
        $result = [];
        foreach ($nodes as $node) {
            if ($node instanceof \SimpleXMLElement) {
                $result[] = trim((string) $node);
            } elseif (is_string($node)) {
                $result[] = trim($node);
            }
        }

        return $result;
    }

    /**
     * @param string[] $array
     *
     * @return string[]
    */
    private static function sanitizeArray(array $array): array
    {
        $sanitized = [];
        foreach ($array as $item) {
            $sanitized[] = trim($item);
        }

        return $sanitized;
    }
}
