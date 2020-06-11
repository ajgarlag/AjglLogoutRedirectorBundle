# [CHANGELOG](http://keepachangelog.com/)
All notable changes to this project will be documented in this file.
This project adheres to [Semantic Versioning](http://semver.org/).

## [Unreleased][unreleased]

### Added

* Add new `LogoutRedirector` class

### Changed

* **[BC Break]** Most `LogoutSuccessHandler` logic moved to `LogoutRedirector`
* **[BC Break]** Config key `handlers` renamed to `redirectors`

## [0.0.2] - 2020-04-15

### Fixed

  * Add method call to set firewall map to logout success handler

## 0.0.1 - 2019-06-04

Initial implementation


[unreleased]: https://github.com/ajgarlag/AjglLogoutRedirectorBundle/compare/0.0.2...master
[0.0.2]: https://github.com/ajgarlag/AjglLogoutRedirectorBundle/compare/0.0.1...0.0.2
