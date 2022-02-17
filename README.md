# SAML Extension for TYPO3 (WIP)

This TYPO3 extension allows you to integrate Single Sign-On (SSO) via SAML. It is possible to sign-on with Azure AD in TYPO3 Backend

## Version compatibility

The following table shows which versions of this package are compatible with which TYPO3 version.

* Version 1 will be compatible to TYPO3 `10.4`.
* Version 2 will be compatible to TYPO3 `10.4` and `11.5`.

Version 2, will require PHP `8.0`.

|           | 1.x |    2.x   |
|-----------|:----:|:--------:|
| TYPO3 v11 |  ❌  |    ✅    |
| TYPO3 v10 |  ✅  |    ✅    |
| PHP 8.0   |  ✅  |    ✅    |
| PHP 7.4   |  ✅  |    ❌    |

## Installation

This extension only works when installed in composer mode. If you are not familiar using composer together with TYPO3
yet you can find a [how to on the TYPO3 website](https://composer.typo3.org/).

Install the extension with the following command:

```bash
composer require cvc/typo3-saml
```

## Getting started

In the extension configuration you need to place the authSource of SimpleSAMLphp (default is "default-sp").

Optionally you can set the id of the be_group new users should join on first login.

## Documentation

The documentation is still in progress and will released with Version 1.0.
