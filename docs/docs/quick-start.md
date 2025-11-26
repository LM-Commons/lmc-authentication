---
title: Quick Start
sidebar_position: 1
---

Lmc Authentication offers middleware to implement authentication in your
application.

Lmc Authentication works in the same way as Mezzio Authentication except that
it does not perform any action when authentication does result into a user.

## Requirements

- PHP 8.2 or higher

## Installation

lmc-authentication only officially supports installation through Composer.

Install the module:

```sh
$ composer require lm-commons/lmc-authentication
```

You will be prompted by the Laminas Component Installer plugin to inject the
component into your `config/config.php` file.
