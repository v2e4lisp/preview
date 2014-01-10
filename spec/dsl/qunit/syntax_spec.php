<?php
namespace Preview\DSL\BDD;

use Preview\Preview;
use Preview\World;
use Preview\Configuration;
use Preview\DSL\qunit;

require_once __DIR__.'/../../helper.php';

describe("qunit[syntax]", function () {
    before_each(function () {
        $this->test_world = Preview::$world;
        $this->test_config = Preview::$config;
    });

    before_each(function () {
        $this->world = new World;
        $this->config = new Configuration;
        $this->config->reporter = new \Recorder;
    });


    describe("#suite", function () {
        it("accept an string as argument ".
           "and create a test suite");
    });

    describe("#test", function () {
        it("accept an string and a closure as arguments ".
           "and create a testcase with desciption");

        it("accept an closure as an argument and ".
           "create a noname testcase");

        it("accept an string as an argument ".
           "an create a pending test");
    })

    describe("#setup", function () {
        it("accept an closure as argument");
    });

    describe("#teardown", function () {
        it("accept an closure as argument");
    });
});


