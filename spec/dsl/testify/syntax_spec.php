<?php

namespace Preview\DSL\BDD;
use Preview\DSL\Testify\Suite as Suite;

use Preview\Preview;
use Preview\World;
use Preview\Configuration;

require_once __DIR__.'/../../helper.php';

describe("testify[context]", function () {
    before_each(function () {
        $this->test_world = Preview::$world;
        $this->test_config = Preview::$config;
    });

    before_each(function () {
        $this->world = new World;
        $this->config = new Configuration;
        $this->config->reporter = new \Recorder;
    });

    describe("Suite", function () {
        it("accepts one string as an test suite description");

        it("accepts multiple strings one as its title, ".
           "others are test groups or 'skip' to mark itself as skipped ");

        describe("#test", function () {
            it("accepts a string and create a pending test");
            it("accepts a closure and create a noname test");
            it("accepts a string and a closure ".
               "and create a test with description");
            it("other args are test groups or 'skip'");
        });

        describe("before_each", function () {
            it("accepts a closure");
        });

        describe("after_each", function () {
            it("accepts a closure");
        });

        describe("before", function () {
            it("accepts a closure");
        });

        describe("after", function () {
            it("accepts a closure");
        });
    });
});

