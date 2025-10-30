<?php

declare(strict_types=1);

/**
 * Copyright notice
 *
 * (c) 2025 Oliver Thiele <mail@oliver-thiele.de>, Web Development Oliver Thiele
 * All rights reserved
 * This script is part of the TYPO3 project. The TYPO3 project is
 * free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 3 of the License, or
 * (at your option) any later version.
 * The GNU General Public License can be found at
 * http://www.gnu.org/copyleft/gpl.html.
 * This script is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * This copyright notice MUST APPEAR in all copies of the script!
 */

namespace OliverThiele\OtMarkdown\Service;

use League\CommonMark\Environment\Environment;
use League\CommonMark\Extension\CommonMark\CommonMarkCoreExtension;
use League\CommonMark\Extension\GithubFlavoredMarkdownExtension;
use League\CommonMark\MarkdownConverter;
use Symfony\Component\Yaml\Yaml;
use TYPO3\CMS\Core\Resource\FileInterface;
use TYPO3\CMS\Core\Resource\ResourceFactory;
use TYPO3\CMS\Core\Utility\GeneralUtility;

final class MarkdownService
{
    private MarkdownConverter $converter;

    /** @var array<string, mixed> */
    private array $frontmatter = [];

    public function __construct()
    {
        $this->frontmatter = [];

        // Configure the standard Markdown environment
        $environment = new Environment([
            'html_input' => 'strip', // protects against unwanted HTML
            'allow_unsafe_links' => false,
        ]);

        // GitHub-Flavored Markdown (Table, Tasklists, Codefences, etc.)
        $environment->addExtension(new CommonMarkCoreExtension());
        $environment->addExtension(new GithubFlavoredMarkdownExtension());

        $this->converter = new MarkdownConverter($environment);
    }

    /**
     * Renders Markdown text to HTML.
     *
     * @param string|null $markdown Inline-Markdown-Text
     * @param FileInterface|string|null $file FAL file object or file path
     * @return string
     * @throws \League\CommonMark\Exception\CommonMarkException
     */
    public function render(?string $markdown = null, FileInterface|string|null $file = null): string
    {
        // 🧹 Reset frontmatter before each render
        $this->frontmatter = [];

        $source = '';

        if (!empty($markdown)) {
            $source = $markdown;
        } elseif (!empty($file)) {
            $source = $this->getFileContent($file);
        }

        if (trim($source) === '') {
            return '';
        }

        // Remove front matter (YAML) and save if necessary
        $source = $this->stripFrontmatter($source);

        // Markdown → HTML
        $result = $this->converter->convert($source);

        return (string)$result;
    }

    /**
     * Returns the parsed front matter data.
     *
     * @return array<string, mixed>
     */
    public function getFrontmatter(): array
    {
        return $this->frontmatter;
    }

    /**
     * Reads the contents of a Markdown file.
     *
     * @param FileInterface|string $file
     * @return string
     */
    private function getFileContent(FileInterface|string $file): string
    {
        // Already handled: $file is FileInterface
        if ($file instanceof FileInterface) {
            try {
                return (string)$file->getContents();
            } catch (\Throwable $e) {
                return sprintf('<!-- Unable to read file: %s (%s) -->', $file->getName(), $e->getMessage());
            }
        }

        // So now $file is guaranteed to be string
        $resourceFactory = GeneralUtility::makeInstance(ResourceFactory::class);
        try {
            $falFile = is_numeric($file)
                ? $resourceFactory->getFileObject((int)$file)
                : $resourceFactory->retrieveFileOrFolderObject($file);

            return $falFile instanceof FileInterface
                ? (string)$falFile->getContents()
                : '';
        } catch (\Throwable $e) {
            return sprintf('<!-- Unable to retrieve file: %s (%s) -->', $file, $e->getMessage());
        }
    }

    /**
     * Removes the frontmatter from the given content.
     * If the content starts with a YAML block enclosed within '---',
     * this block will be stripped from the content.
     *
     * @param string $content The input string potentially containing a frontmatter block.
     * @return string The content without the frontmatter block, trimmed at the beginning.
     */
    private function stripFrontmatter(string $content): string
    {
        $this->frontmatter = []; // reset default

        if (preg_match('/^---\s*\n(.*?)\n---\s*\n/s', $content, $matches)) {
            $yamlBlock = $matches[1];
            try {
                $this->frontmatter = Yaml::parse($yamlBlock) ?? [];
            } catch (\Throwable) {
                $this->frontmatter = [];
            }
            $content = preg_replace('/^---\s*\n.*?\n---\s*\n/s', '', $content);
        }

        if (isset($this->frontmatter['date'])) {
            $timestamp = strtotime((string)$this->frontmatter['date']);
            if ($timestamp === false || $timestamp <= 0) {
                // Invalid or unparseable date → remove it
                unset($this->frontmatter['date']);
            } else {
                // Normalize to ISO 8601 (YYYY-MM-DD)
                $this->frontmatter['date'] = date('Y-m-d', $timestamp);
            }
        }

        return ltrim((string)$content);
    }
}
