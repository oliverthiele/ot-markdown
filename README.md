
# ot_markdown — TYPO3 Extension for Markdown Content Elements

## 🧩 Overview

`oliverthiele/ot-markdown` adds a modern and accessible **Markdown content element** and a **Fluid ViewHelper** to TYPO3
v13+.
It supports both inline Markdown (entered directly in the backend) and Markdown files from the Fileadmin.
The extension uses [league/commonmark](https://commonmark.thephpleague.com/) for Markdown parsing and optionally
supports syntax highlighting via [Prism.js](https://prismjs.com/).

---

## 🚀 Features

- TYPO3 v13+ compatible (Site Set ready)
- Inline or file-based Markdown rendering
- Optional syntax highlighting with Prism.js (toggle via Site Set or Constant Editor)
- Support for frontmatter metadata (`title`, `author`, …)
- Accessible output using semantic `<section>` and `<figure>` elements
- Reusable `MarkdownViewHelper` for custom integrations
- Fully configurable via TypoScript and Site Set Settings

---

## 🧱 Installation

Install via Composer:

```bash
composer require oliverthiele/ot-markdown
```

After installation, activate the **Site Set “ot_markdown”** in your TYPO3 backend.

---

## 🖋️ Usage

### As a Content Element

Choose **“Markdown”** as content type (`CType = ot_markdown`).
You can either:
- enter Markdown directly into the text field, or
- select a `.md`, `.markdown` or `.txt` file from Fileadmin.

The content element automatically renders semantic HTML.

### As a ViewHelper

You can also use the ViewHelper directly in your Fluid templates:

```html
<ot:markdown text="{data.bodytext}"/>

<ot:markdown file="{file}" as="output">
    <f:format.raw>{output.html}</f:format.raw>
</ot:markdown>

<ot:markdown file="/fileadmin/…/Example.md" as="output">
    <f:format.raw>{output.html}</f:format.raw>
</ot:markdown>
```

The variable `{output.frontmatter}` provides access to YAML metadata (frontmatter) from Markdown files.

---

## ⚙️ Configuration

### Site Set Setting

You can toggle CDN loading for Prism syntax highlighting:

```yaml
settings:
    OtMarkdown.useCdnForPrism:
        type: bool
        default: true
```

The corresponding TypoScript mapping is already included:

```typoscript
settings {
  otMarkdown {
    useCdnForPrism = {$settings.OtMarkdown.useCdnForPrism}
  }
}
```

### TypoScript Configuration

Default TypoScript paths:

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

---

### Classic TypoScript Integration (without Site Set)
If you do not use the Site Set integration, you can import the TypoScript files manually in your sitepackage:

```typoscript
@import 'EXT:ot_markdown/Configuration/TypoScript/constants.typoscript'
@import 'EXT:ot_markdown/Configuration/TypoScript/setup.typoscript'
```

This makes the Markdown content element and ViewHelper available without activating the Site Set in the backend.

After including these files, you can also manage options such as **“Use CDN for Prism syntax highlighter”** via the Constant Editor under the category **OtMarkdown**.

---

## 🧩 Accessibility & Semantics

Each Markdown block is wrapped in a `<section>` element for accessible structure.
Markdown files are rendered inside `<figure>` tags with optional `<figcaption>` generated from frontmatter (`title`,
`author`).

This ensures proper structure even when multiple Markdown elements are used on one page.

---

## 💡 Developer Notes

The parsing logic is encapsulated in a `MarkdownService` class and reused in the `MarkdownViewHelper`.
This allows Markdown rendering in controllers, commands, or custom integrations.

Example usage in PHP:

```php
$service = GeneralUtility::makeInstance(MarkdownService::class);
$html = $service->render('# Hello World');
```

---

## 🧩 License

GPL-2.0-or-later
© 2025 Oliver Thiele

---

## 📦 Packagist / TER Metadata

**Name:** `oliverthiele/ot-markdown`
**Type:** `typo3-cms-extension`
**Extension Key:** `ot_markdown`
