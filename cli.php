<?php

namespace Preview;

// require_once './vendor/autoload.php';
require_once 'lib/reporter/default.php';
require_once 'lib/configuration.php';
require_once 'lib/world.php';
require_once 'lib/dsl/bdd.php';
require_once 'lib/loader.php';

Configuration::setReporter(new DefaultReporter);
// Preview\Configuration::setAssertionErrors();
foreach ($argv as $arg) {
    Loader::load($arg);
}
World::run();
