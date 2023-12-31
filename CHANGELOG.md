## [2.0.1](https://github.com/miaoxing/phpstan-miaoxing/compare/v2.0.0...v2.0.1) (2023-12-31)


### Bug Fixes

* **ClassMap:** 类重复时，记录类名错误 ([eddce0c](https://github.com/miaoxing/phpstan-miaoxing/commit/eddce0c9513ebd25b325b6753c9b070ec5619c19))
* **phpstan-miaoxing:** remove bleedingEdge config, only contains release rules ([03b14d8](https://github.com/miaoxing/phpstan-miaoxing/commit/03b14d897314c09a4b5d6549a76c6abdfb6274b2))

# [2.0.0](https://github.com/miaoxing/phpstan-miaoxing/compare/v1.2.1...v2.0.0) (2023-11-30)


### Bug Fixes

* **phpstan:** error when class not has no traits ([d66421c](https://github.com/miaoxing/phpstan-miaoxing/commit/d66421c264bc0f8c015c5ef18ce2486d79c0ada2)), closes [#1](https://github.com/miaoxing/miaoxing/issues/1)
* **phpstan-miaoxing:** Union 类型不能嵌套 ([b9d7bcb](https://github.com/miaoxing/phpstan-miaoxing/commit/b9d7bcb044f5874a3009a249a1dba51954782c6b))


### Features

* **phpstan:** upgrade phpstan to 1.x ([58b4ca9](https://github.com/miaoxing/phpstan-miaoxing/commit/58b4ca9cb7975970277e85abf646675325c50c2f))
* **phpstan-miaoxing:** 增加 `ModelReturnTypeExtension` 扩展，解决 Model 返回值被 PHPStan 识别为 `ModelTrait` 的问题 ([922d611](https://github.com/miaoxing/phpstan-miaoxing/commit/922d611968048a1967a5405b0b19d4574e6a0015))


### BREAKING CHANGES

* **phpstan:** upgrade phpstan to 1.x

## [1.2.1](https://github.com/miaoxing/phpstan-miaoxing/compare/v1.2.0...v1.2.1) (2023-09-02)


### Bug Fixes

* **ClassMap:** 解决过滤重复类错误 ([b95cfca](https://github.com/miaoxing/phpstan-miaoxing/commit/b95cfcafdc79f520e1a0b430ced4ea0881d74259))

# [1.2.0](https://github.com/miaoxing/phpstan-miaoxing/compare/v1.1.0...v1.2.0) (2023-07-31)


### Features

* **classMap:** 同步 `classMap` 更新，支持多级继承的情况 ([df9bd6e](https://github.com/miaoxing/phpstan-miaoxing/commit/df9bd6e78800d667762ac15df77ccf8f0ca59f60))

# [1.1.0](https://github.com/miaoxing/phpstan-miaoxing/compare/v1.0.0...v1.1.0) (2022-08-02)


### Features

* **phpstan-miaoxing:** 兼容校验器的新调用方式 ([1a0dfc7](https://github.com/miaoxing/phpstan-miaoxing/commit/1a0dfc75db3c74178f76f3ebce2f324218494fad))

# [1.0.0](https://github.com/miaoxing/phpstan-miaoxing/compare/v0.1.1...v1.0.0) (2022-07-01)


### Features

* 发布 1.0.0 [release 1.0.0] ([9c9828a](https://github.com/miaoxing/phpstan-miaoxing/commit/9c9828a8f9e9a27d25d26d9cb8e7a01e26e7f22e))

## [0.1.1](https://github.com/miaoxing/phpstan-miaoxing/compare/v0.1.0...v0.1.1) (2021-05-12)


### Bug Fixes

* **phpstan-miaoxing:** 发布新版，解决同步错误导致内容未发布 ([7178429](https://github.com/miaoxing/phpstan-miaoxing/commit/71784297b0ad9aab22fa6ea8e2b4c89c2282316b))

# 0.1.0 (2021-05-11)


### Code Refactoring

* PHPStan 功能移到到 `miaoxing/phpstan-miaoxing` 中 ([b35be73](https://github.com/miaoxing/phpstan-miaoxing/commit/b35be73b1d44ef77aea65c87a5ba981c8bea3d87))


### BREAKING CHANGES

* PHPStan 功能移到到 `miaoxing/phpstan-miaoxing` 中
