<?php

namespace Preview\DSL\BDD;

use Preview\Preview;
use Preview\World;
use Preview\Configuration;

require_once __DIR__."/../../helper.php";

shared_example("pending test", function () {
    describe("#runnable", function () {
        it ("should return false", function () {
            ok(!$this->subject->runnable());
        });
    });

    describe("#skip", function () {
        before_each(function () {
            $this->subject->skip();
        });

        it("should not work", function () {
            ok(!$this->subject->skipped);
        });
    });

    describe("#time", function () {
        it("should return null", function () {
            ok(is_null($this->subject->time()));
        });
    });
});

shared_example("skipped test", function () {
    describe("#runnable", function () {
        it ("should return false", function () {
            ok(!$this->subject->runnable());
        });
    });

    describe("#time", function () {
        it("should return null", function () {
            ok(is_null($this->subject->time()));
        });
    });
});

shared_example("finished test", function () {
    describe("#runnable", function () {
        it ("should return false", function () {
            ok(!$this->subject->runnable());
        });
    });

    describe("#time", function () {
        it("should return numeric value", function () {
            ok(is_numeric($this->subject->time()));
        });
    });
});

shared_example("groupable test", function () {
    describe('defaut $groups', function () {
        it ("should be empty array", function () {
            ok($this->subject->groups == array());
        });
    });

    describe("#add_to_group", function () {
        it ('should add group to $groups', function () {
            $old_world = Preview::$world;
            Preview::$world = new World;

            $this->subject->add_to_group("group-1");
            ok($this->subject->groups == array("group-1"));

            Preview::$world = $old_world;
        });
    });

    describe("#in_test_group", function () {
        context("when no test group specified in config", function () {
            it("should return true", function () {
                $old_config = Preview::$config;
                $old_world = Preview::$world;
                Preview::$config = new Configuration;
                Preview::$world = new World;

                $in = $this->subject->in_test_group();

                Preview::$config = $old_config;
                Preview::$world = $old_world;
                ok($in);
            });
        });

        context("when test groups are specified in config", function () {
            context("and test is not added to test groups", function () {
                it ("should return false", function () {
                    $old_config = Preview::$config;
                    Preview::$config = new Configuration;
                    Preview::$config->test_groups = array("group-1");

                    $in = $this->subject->in_test_group();

                    Preview::$config = $old_config;
                    ok(!$in);
                });
            });

            context("and test is added to test groups", function () {
                it ("should return true", function () {
                    $old_config = Preview::$config;
                    $old_world = Preview::$world;
                    Preview::$config = new Configuration;
                    Preview::$world = new World;
                    Preview::$config->test_groups = array("group-1");

                    $this->subject->add_to_group("group-1");
                    $in = $this->subject->in_test_group();

                    Preview::$config = $old_config;
                    Preview::$world = $old_world;
                    ok($in);
                });
            });
        });
    });
});

shared_example("having its own hooks to run", function ($hook) {
    describe("#run_$hook", function () use ($hook) {
        it("should run $hook hooks", function () use ($hook) {
            $old_world = Preview::$world;
            $run = false;
            $add_hook = "add_{$hook}_hook";
            $run_hook = "run_{$hook}";

            $this->subject->$add_hook(function () use (&$run) {
                $run = true;
            });
            $this->subject->$run_hook();
            Preview::$world = $old_world;

            ok($run);
        });
    });
});

shared_example("having hooks for its children", function ($hook) {
    describe("#run_$hook", function () use ($hook) {
        it("should run $hook hooks and its parents'", function () use ($hook) {
            $old_world = Preview::$world;

            $run = false;
            $parent_run = false;
            $add_hook = "add_{$hook}_hook";
            $run_hook = "run_{$hook}";

            $this->parent->$add_hook(function () use (&$parent_run) {
                $parent_run = true;
            });
            $this->subject->$add_hook( function () use (&$run) {
                $run = true;
            });

            $context = new \stdClass;
            $this->subject->$run_hook($context);

            Preview::$world = $old_world;

            ok($run and $parent_run);
        });
    });
});