<?php

declare(strict_types=1);

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
 *
 * @extends AbstractViewHelper<array{html: string, frontmatter: array<string, mixed>}>
 */
final class MarkdownViewHelper extends AbstractViewHelper
{
    protected $escapeOutput = false;

    public function __construct(
        private readonly MarkdownService $markdownService
    ) {}

    public function initializeArguments(): void
    {
        $this->registerArgument('text', 'string', 'Markdown text', false, '');
        $this->registerArgument('file', 'mixed', 'File object or path', false, null);
        $this->registerArgument('as', 'string', 'Optional variable name for assigning output array', false, '');
    }

    public function render(): string
    {
        if ($this->markdownService === null) {
            return '<!-- MarkdownService not available -->';
        }

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
