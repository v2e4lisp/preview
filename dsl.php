<?php
require_once './world.php';
require_once './core.php';

function describe($title, $fn) {
    $desc = new TestSuite($title, $fn);
    $desc->set_parent(World::current());
    World::push($desc);
    $desc->setup();
    World::pop();
    return $desc;
}

function context($title, $fn) {
    return describe($title, $fn);
}

function it($title, $fn) {
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