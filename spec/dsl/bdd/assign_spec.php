<?php
namespace Preview\DSL\BDD;

use Preview\Preview;
use Preview\World;
use Preview\Configuration;

require_once __DIR__.'/../../helper.php';

describe("bdd", function () {
    before_each(function () {
        $this->test_world = Preview::$world;
        $this->test_config = Preview::$config;
    });

    before_each(function () {
        $this->world = new World;
        $this->config = new Configuration;
        $this->config->reporter = new \Recorder;
    });

    describe("#let", function () {
        it("should add variable to current test case context", function () {
            // start new env
            Preview::$world = $this->world;
            Preview::$config = $this->config;

            describe("sample suite", function () {
                let("user", "wenjun.yan");

                it("sample case", function () {
                    ok($this->user == "wenjun.yan");
                });
            });

            $results = Preview::$world->run();

            // end new env
            Preview::$world = $this->test_world;
            Preview::$config = $this->test_config;
            $result = $results[0];
            ok($result->passed());
        });

        context("when object passed in", function () {
            it("should clone the object first then assignment", function() {
                // start new env
                Preview::$world = $this->world;
                Preview::$config = $this->config;

                describe("sample suite", function () {
                    let("user", new \stdClass);

                    after_each(function () {
                        $this->user->name = "wenjun.yan";
                    });

                    it("should not see the property in after each", function () {
                        ok(array() == (array) $this->user);
                    });

                    it("should not see the property in after each", function () {
                        ok(array() == (array) $this->user);
                    });
                });
                $results = Preview::$world->run();

                // end new env
                Preview::$world = $this->test_world;
                Preview::$config = $this->test_config;
                $result = $results[0];
                ok($result->passed());
            });
        });

        context("when closure passed in", function () {
            it("should invoke closure with current case context", function () {
                // start new env
                Preview::$world = $this->world;
                Preview::$config = $this->config;

                describe("sample suite", function () {
                    before_each(function () {
                        $this->users = array("wenjun", "yan");
                    });

                    let("total_users", function () {
                        return count($this->users);
                    });

                    it("should have total users", function () {
                        ok($this->total_users == count($this->users));
                    });

                });
                $results = Preview::$world->run();

                // end new env
                Preview::$world = $this->test_world;
                Preview::$config = $this->test_config;
                $result = $results[0];
                ok($result->passed());
            });
        });
    });

    describe("#let", function () {
        it("should assign value to subject", function () {
            // start new env
            Preview::$world = $this->world;
            Preview::$config = $this->config;

            describe("sample suite", function () {
                subject("wenjun.yan");

                it("sample case", function () {
                    ok($this->subject == "wenjun.yan");
                });
            });

            $results = Preview::$world->run();

            // end new env
            Preview::$world = $this->test_world;
            Preview::$config = $this->test_config;
            $result = $results[0];
            ok($result->passed());
        });

        context("when object passed in", function () {
            it("should clone the object first then assignment", function() {
                // start new env
                Preview::$world = $this->world;
                Preview::$config = $this->config;

                describe("sample suite", function () {
                    subject(new \stdClass);

                    after_each(function () {
                        $this->subject->name = "wenjun.yan";
                    });

                    it("should not see the property in after each", function () {
                        ok(array() == (array) $this->subject);
                    });

                    it("should not see the property in after each", function () {
                        ok(array() == (array) $this->subject);
                    });
                });
                $results = Preview::$world->run();

                // end new env
                Preview::$world = $this->test_world;
                Preview::$config = $this->test_config;
                $result = $results[0];
                ok($result->passed());
            });
        });

        context("when closure passed in", function () {
            it("should invoke closure with current case context", function () {
                // start new env
                Preview::$world = $this->world;
                Preview::$config = $this->config;

                describe("sample suite", function () {
                    before_each(function () {
                        $this->users = array("wenjun", "yan");
                    });

                    subject(function () {
                        return count($this->users);
                    });

                    it("should have total users", function () {
                        ok($this->subject == count($this->users));
                    });

                });
                $results = Preview::$world->run();

                // end new env
                Preview::$world = $this->test_world;
                Preview::$config = $this->test_config;
                $result = $results[0];
                ok($result->passed());
            });
        });
    });
});
