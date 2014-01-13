# Intro

## config

`preview.config.php` in current dir will be autoloaded, if you do not
specify a config file by (-c, --config file)

- [default config](./preview.config.php)

## dsl syntax

- [bdd](./bdd)
- [tdd](./tdd)
- [qunit](./qunit)
- [export](./export)
- [testify](./testify)


## Command Line Options

```
-> % php preview --help

    Preview 1.0 - bdd test framework for php

Usage: preview [options] [operands]
Options:
-h, --help              Show this help message.
-r, --reporter <arg>    Set reporter.
-g, --group <arg>       Test group(s). use comma(,) to seperate groups.
-c, --config <arg>      Load config file. Default './preview.config.php'.
--order                 Test are run in order.
--no-this               Disable using $this as an implicit context. PHP 5.4 only.
--no-color              Disable color output.
--with-error            Disable converting error to exception.
--fail-fast             Exit program when first failure or error occurred.
--list-groups           List all the test groups.
--list-reporters        List available reporters.
```
