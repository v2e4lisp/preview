<?php
namespace Preview\DSL\BDD;

use Preview\Preview;
use Preview\World;
use Preview\Configuration;
use Preview\DSL\Export;

require_once __DIR__.'/../../helper.php';

describe("export[context]", function () {
    before_each(function () {
        $this->test_world = Preview::$world;
        $this->test_config = Preview::$config;
    });

    before_each(function () {
        $this->world = new World;
        $this->config = new Configuration;
        $this->config->reporter = new \Recorder;
    });

    context("test suite", function () {
        it("share the context with before/after hook", function () {
            // start new env
            Preview::$world = $this->world;
            Preview::$config = $this->config;
            $tmp = null;

            try {
                $suite = [
                    "before" => function () use (&$tmp) {
                        $tmp = $this;
                        $this->user = "wenjun.yan";
                    },

                    "context diff from suite context" => function () use (&$tmp) {
                        ok($this !== $tmp);
                    },

                    "after" => function () use (&$tmp) {
                        ok($this === $tmp);
                    },
                ];

                Export\export($suite);
                $results = Preview::$world->run();
            } finally {
                // end new env
                Preview::$world = $this->test_world;
                Preview::$config = $this->test_config;
            }

            $result = $results[0];
            ok($result->passed());
        });

        it("extend the context of its parent test suite", function () {
            // start new env
            Preview::$world = $this->world;
            Preview::$config = $this->config;

            try {
                $suite = [
                    "before" => function () {
                        $this->shared = true;
                    },

                    "access suite context" => function () {
                        ok($this->shared);
                    },
                ];
                Export\export($suite);
                $results = Preview::$world->run();
            } finally {
                // end new env
                Preview::$world = $this->test_world;
                Preview::$config = $this->test_config;
            }
            $result = $results[0];
            ok($result->passed());
        });
    });

    context("test case", function (){
        it("share the context with before/after each hook", function () {
            // start new env
            Preview::$world = $this->world;
            Preview::$config = $this->config;
            $context_tmp = null;

            try {
                $suite = [
                    "before each" => function () use (&$context_tmp) {
                        $this->user = "wenjun.yan";
                        $context_tmp = $this;
                    },

                    "should have a user" => function () use (&$context_tmp) {
                        ok($this === $context_tmp, "what");
                    },

                    "after each" => function () use (&$context_tmp) {
                        ok($this === $context_tmp, "here");
                    },
                ];
                Export\export($suite);
                $results = Preview::$world->run();
            } finally {
                // end new env
                Preview::$world = $this->test_world;
                Preview::$config = $this->test_config;
            }

            $result = $results[0];
            ok($result->passed());
        });

        it("should extend test suite context", function () {
            // start new env
            Preview::$world = $this->world;
            Preview::$config = $this->config;

            try {
                $suite = [
                    "before" => function () {
                        $this->user = "wenjun.yan";
                    },

                    "before_each" => function () {
                        ok($this->user == "wenjun.yan");
                    },

                    "should have a user" => function () {
                        ok($this->user == "wenjun.yan");
                    },

                    "after_each" => function () {
                        ok($this->user == "wenjun.yan");
                    },
                ];
                Export\export($suite);
                $results = Preview::$world->run();
            } finally {
                // end new env
                Preview::$world = $this->test_world;
                Preview::$config = $this->test_config;
            }

            $result = $results[0];
            ok($result->passed());
        });
    });
});
