# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

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