<?php

namespace Mocha\BDD;

require_once __DIR__.'/../world.php';
require_once __DIR__.'/../core.php';

use \Mocha\World;
use \Mocha\TestSuite;
use \Mocha\TestCase;

function describe($title, $fn=null) {
    $desc = new TestSuite($title, $fn);
    $desc->set_parent(World::current());
    World::push($desc);
    $desc->setup();
    World::pop();
    return $desc;
}

function context($title, $fn=null) {
    return describe($title, $fn);
}

function it($title, $fn=null) {
    $case = new TestCase($title, $fn);
    $case->set_parent(World::current());
    return $case;
}

function before($fn) {
    World::current()->before_hooks[] = $fn;
}

function after($fn) {
    World::current()->after_hooks[] = $fn;
}

function beforeEach($fn) {
    World::current()->before_each_hooks[] = $fn;
}

function afterEach($fn) {
    World::current()->before_after_hooks[] = $fn;
}