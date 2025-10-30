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

namespace OliverThiele\OtMarkdown\ViewHelpers;

use OliverThiele\OtMarkdown\Service\MarkdownService;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;

/**
 * Renders Markdown content to HTML.
 * Supports file or inline text and can expose metadata (frontmatter).
 *
 * Example:
 *   <ot:markdown text="{data.bodytext}" />
 *   <ot:markdown file="{file}" as="output" />
 *   <ot:markdown>{variableWithMarkdown}</ot:markdown>
 */
final class MarkdownViewHelper extends AbstractViewHelper
{
    protected $escapeOutput = false;

    public function __construct(
        private readonly MarkdownService $markdownService
    ) {
    }

    public function initializeArguments(): void
    {
        $this->registerArgument('text', 'string', 'Markdown text', false, '');
        $this->registerArgument('file', 'mixed', 'File object or path', false, null);
        $this->registerArgument('as', 'string', 'Optional variable name for assigning output array', false, '');
    }

    public function render(): string
    {
        $text = trim((string)$this->arguments['text']);
        $file = $this->arguments['file'] ?? null;
        $as = (string)$this->arguments['as'];

        if ($text === '' && $file === null) {
            $text = trim((string)$this->renderChildren());
        }

        $html = $this->markdownService->render($text, $file);
        $frontmatter = $this->markdownService->getFrontmatter();

        // If "as" is specified, provide variable in template
        if ($as !== '') {
            $variableProvider = $this->renderingContext->getVariableProvider();
            $variableProvider->add($as, [
                'html' => $html,
                'frontmatter' => $frontmatter,
            ]);

            $content = $this->renderChildren();

            $variableProvider->remove($as);
            return (string)$content;
        }

        // Default behaviour: return HTML only
        return $html;
    }
}
