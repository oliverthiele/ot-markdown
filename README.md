# ot_markdown — Markdown Content Element for TYPO3

Adds a Markdown content element and a Fluid ViewHelper to TYPO3 v13 and v14. Supports inline Markdown and `.md` files
from Fileadmin, rendered via [league/commonmark](https://commonmark.thephpleague.com/) with optional Prism.js syntax
highlighting.

[![TYPO3](https://img.shields.io/badge/TYPO3-13.4-orange.svg)](https://typo3.org/)
[![Packagist Version](https://img.shields.io/packagist/v/oliverthiele/ot-markdown.svg)](https://packagist.org/packages/oliverthiele/ot-markdown)
[![PHP](https://img.shields.io/packagist/dependency-v/oliverthiele/ot-markdown/php.svg)](https://php.net/)
[![License](https://img.shields.io/packagist/l/oliverthiele/ot-markdown.svg)](LICENSE)
[![Changelog](https://img.shields.io/badge/Changelog-CHANGELOG.md-blue.svg)](CHANGELOG.md)

## Features

- TYPO3 v13+ compatible (Site Set ready)
- Inline or file-based Markdown rendering (`.md`, `.markdown`, `.txt`)
- Optional syntax highlighting via Prism.js (CDN toggle)
- Frontmatter metadata support (`title`, `author`, …)
- Accessible output using semantic `<section>` and `<figure>` elements
- Reusable `MarkdownViewHelper` for custom Fluid templates
- Configurable via Site Set settings and TypoScript

## Requirements

| Requirement       | Version        |
|-------------------|----------------|
| TYPO3             | ^13.4 \| ^14.3 |
| PHP               | >=8.3          |
| league/commonmark | ^2.7           |

## Installation

```bash
composer require oliverthiele/ot-markdown
```

After installation, activate the **Site Set "OtMarkdown"** for your site in the TYPO3 backend.

## Configuration

### Site Set Settings

| Key                         | Type | Default | Description            |
|-----------------------------|------|---------|------------------------|
| `OtMarkdown.useCdnForPrism` | bool | `true`  | Load Prism.js from CDN |

### TypoScript

The TypoScript is auto-included via the Site Set. For manual integration without Site Set:

```typoscript
@import 'EXT:ot_markdown/Configuration/TypoScript/constants.typoscript'
@import 'EXT:ot_markdown/Configuration/TypoScript/setup.typoscript'
```

Default content element configuration:

```typoscript
tt_content.ot_markdown =< lib.contentElement
tt_content.ot_markdown {
    templateName = Markdown
    dataProcessing {
        10 = TYPO3\CMS\Frontend\DataProcessing\FilesProcessor
        10.references.fieldName = assets
        10.as = markdownFiles
    }
}
```

## Usage

### Content Element

Select **"Markdown"** as content type (`CType = ot_markdown`) in the TYPO3 backend. Choose between:

- **Inline** — enter Markdown directly in the text field
- **File** — select a `.md`, `.markdown`, or `.txt` file from Fileadmin

### ViewHelper

```html
{namespace ot=OliverThiele\OtMarkdown\ViewHelpers}

<ot:markdown text="{data.bodytext}"/>

<ot:markdown file="{file}" as="output">
    <f:format.raw>{output.html}</f:format.raw>
</ot:markdown>
```

Access frontmatter metadata via `{output.frontmatter.title}`, `{output.frontmatter.author}`, etc.

### PHP API

```php
use OliverThiele\OtMarkdown\Service\MarkdownService;
use TYPO3\CMS\Core\Utility\GeneralUtility;

$service = GeneralUtility::makeInstance(MarkdownService::class);
$html = $service->render('# Hello World');
```

## License

GPL-2.0-or-later — © 2025 Oliver Thiele
