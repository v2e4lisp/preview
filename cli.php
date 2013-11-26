<?php

require_once 'reporter/default.php';
require_once 'configuration.php';
require_once 'world.php';
require_once 'dsl.php';
require_once 'loader.php';

Mocha\Configuration::setReporter(new DefaultReporter);
// Mocha\Configuration::setAssertionErrors();
Mocha\Loader::load("./test");
Mocha\World::run();
