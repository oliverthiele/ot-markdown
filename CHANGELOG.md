# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [3.0.0] — 2026-07-31

### Changed

- **Breaking:** Drop TYPO3 v13 support, require TYPO3 `^14.3`
- **Breaking:** Raise the PHP minimum to `>=8.4`. The `ext_emconf.php`
  constraint also had an upper bound of `8.4.99`, which is now `8.99.99`
- Migrate the language files from XLIFF 1.2 to XLIFF 2.0. Unit identifiers and
  all translations are unchanged, so no label reference needs adjusting
- Reference labels via translation domain mapping instead of full file paths:
  `ot_markdown.db:` replaces
  `LLL:EXT:ot_markdown/Resources/Private/Language/locallang_db.xlf:`, and the
  access tab now uses `core.form.tabs:`

## [2.0.0] — 2026-04-25

### Added

- TYPO3 v14.3 support (`^13.4||^14.3`)

### Changed

- Raise PHP minimum constraint from `^8.2` to `>=8.3`
- Inject `ResourceFactory` via constructor instead of `GeneralUtility::makeInstance()`
- Fix PHPStan error: null-safe access to `renderingContext` in `MarkdownViewHelper`

## [1.0.8] — 2026-03-16

### Fixed

- Remove unsupported `behaviour` setting from TCA `columnsOverrides`

## [1.0.7] — 2026-02-20

### Added

- SiteKit configuration (`Configuration/SiteKit.yaml`)

## [1.0.5] — 2025-11-30

### Fixed

- Fix missing localisation key
- Fix English localisation

## [1.0.4] — 2025-10-30

### Changed

- Rename translation identifiers for automatic use

## [1.0.3] — 2025-10-30

### Changed

- Add PHP version requirement
- Apply PHP CodeSniffer fixes

## [1.0.2] — 2025-10-20

### Added

- Date normalisation support in frontmatter
- Blockquote rendering improvements

### Fixed

- Reset frontmatter state between renders

## [1.0.0] — 2025-10-16

### Added

- Initial release: Markdown content element and `MarkdownViewHelper`
- Inline and file-based rendering (`.md`, `.markdown`, `.txt`)
- Frontmatter (YAML) parsing
- Optional Prism.js syntax highlighting via CDN