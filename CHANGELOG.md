# Changelog

The format is based on [Keep a Changelog](http://keepachangelog.com/en/1.0.0/)
and this project adheres to [Semantic Versioning](http://semver.org/spec/v2.0.0.html).

## [Unreleased]

## [4.0.0] - 2019-06-23

### Changed
- Now using PHPUnit ^8.0
- Roles and permissions custom claim now uses a `$namespace` variable, allowing code reusability

### Removed
- PHP 7.0, PHP 7.1 support

## [3.0.0] - 2019-04-27
First release after fork

### Added
- Custom domain support
- OIDC compliant resource owner
- Roles and permissions custom claims, using hardcoded `http://ckc-zoetermeer.nl` namespace

### Changed
- Default region is now EU
- Changed namespace to DigitalArtLab

### Removed
- PHP 5.6 support