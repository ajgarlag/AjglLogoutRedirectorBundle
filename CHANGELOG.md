# [CHANGELOG](http://keepachangelog.com/)
All notable changes to this project will be documented in this file.
This project adheres to [Semantic Versioning](http://semver.org/).

## [Unreleased][unreleased]

## [0.2.0] - 2023-03-09

### Added

* Add support for Symfony 6.2

### Changed

* Require thecodingmachine/safe:^2.4

### Removed

* Remove `LogoutSuccessHandler`
* Drop support for PHP:<8.0

## [0.1.4] - 2023-03-09

### Deprecated

* Deprecate `LogoutSuccessHandler`

## [0.1.3] - 2023-03-09

### Removed

* Drop support for Symfony<5.4
* Drop support for PHP<7.4
* Drop support for thecodingmachine/safe:<1.3

## [0.1.2] - 2023-03-09

### Added

* Add support for PHP>=8.0

## [0.1.1] - 2020-06-12

### Fixed

* Fix dependencies

## [0.1.0] - 2020-06-12

### Added

* Add new `LogoutRedirector` class
* Add new `LogoutRedirectorEventListener` to support new `LogoutEvent`

### Changed

* **[BC Break]** Most `LogoutSuccessHandler` logic moved to `LogoutRedirector`
* **[BC Break]** Config key `handlers` renamed to `redirectors`

## [0.0.2] - 2020-04-15

### Fixed

  * Add method call to set firewall map to logout success handler

## 0.0.1 - 2019-06-04

Initial implementation


[unreleased]: https://github.com/ajgarlag/AjglLogoutRedirectorBundle/compare/0.2.0...master
[0.2.0]: https://github.com/ajgarlag/AjglLogoutRedirectorBundle/compare/0.1.4...0.2.0
[0.1.4]: https://github.com/ajgarlag/AjglLogoutRedirectorBundle/compare/0.1.3...0.1.4
[0.1.3]: https://github.com/ajgarlag/AjglLogoutRedirectorBundle/compare/0.1.2...0.1.3
[0.1.2]: https://github.com/ajgarlag/AjglLogoutRedirectorBundle/compare/0.1.1...0.1.2
[0.1.1]: https://github.com/ajgarlag/AjglLogoutRedirectorBundle/compare/0.1.0...0.1.1
[0.1.0]: https://github.com/ajgarlag/AjglLogoutRedirectorBundle/compare/0.0.2...0.1.0
[0.0.2]: https://github.com/ajgarlag/AjglLogoutRedirectorBundle/compare/0.0.1...0.0.2
