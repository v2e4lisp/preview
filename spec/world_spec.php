<?php
namespace Preview\DSL\BDD;

use Preview\Preview;
use Preview\World;
use Preview\Core\TestShared;
use Preview\Core\TestCase;
use Preview\Core\TestSuite;
use Preview\Configuration;
use Preview\Reporter\Base as BaseReporter;

require_once 'helper.php';

describe("World", function () {
    before_each(function () {
        $this->world = new World;
    });

    describe("#current", function () {
        context("when nothing pushed in", function () {
            it ("should return null", function () {
                ok(is_null($this->world->current()));
            });
        });
    });

    describe("#push", function () {
        it ("should push item into the stack", function () {
            $this->world->push(1);
            ok($this->world->current() == 1);
        });
    });

    describe("#pop", function () {
        context("when nothing pushed in", function () {
            it ("should return null", function () {
                ok(is_null($this->world->pop()));
            });
        });

        context("when some items pushed in", function () {
            before_each(function () {
                $this->world->push(1);
                $this->world->push(2);
            });

            it ("should return the last item", function () {
                ok($this->world->pop() == 2);
            });

            it ("should remove the last item", function () {
                $this->world->pop();
                ok($this->world->current() == 1);
            });
        });
    });

    describe("#groups", function () {
        context("when no test groups", function () {
            it ("should return an empty array", function () {
                $groups = $this->world->groups();
                ok(is_array($groups) and empty($groups));
            });
        });
    });

    describe("#shared_example", function () {
        context("when no such test", function () {
            it ("should return null", function () {
                ok(is_null($this->world->shared_example("foo")));
            });
        });
    });

    describe("#add_shared_example", function () {
        it('should add this test to $share_examples', function () {
            $shared = new TestShared("foo", function (){});
            $this->world->add_shared_example($shared);
            ok($this->world->shared_example("foo")->name() == "foo");
        });
    });

    describe("#run", function () {
        before_each(function () {
            $this->test_config = Preview::$config;
        });

        before_each(function () {
            $this->config = new Configuration;
            $this->config->reporter = new BaseReporter;
        });

        before_each(function () {
            $this->test1 = new TestSuite("test-1", function (){});
            $this->test1->add(new TestCase("case-1", function () {}));
            $this->case2 = new TestCase("case-2", function () {});
            $this->test1->add($this->case2);
            $this->test2 = new TestSuite("test-2", function (){});
        });

        context("when no test groups specified", function () {
            it ("should run all the tests", function () {
                Preview::$config = $this->config;

                $this->world->push($this->test1);
                $this->world->pop();
                $this->world->push($this->test2);
                $results = $this->world->run();

                Preview::$config = $this->test_config;

                $case_total = 0;
                foreach($results as $suite) {
                    $case_total += count($suite->cases());
                }

                ok(count($results) == 2);
                ok($case_total == 2);
            });
        });

        context("when test groups specified", function () {
            before_each(function () {
                $this->config->test_groups = array("group-1");
                $this->test3 = new TestSuite("test-3", function (){});
                $this->test3->add_to_group("group-1");
                $this->case2->add_to_group("group-1");
            });

            it("should only run tests in the test groups", function () {
                Preview::$config = $this->config;

                $this->world->push($this->test1);
                $this->world->pop();
                $this->world->push($this->test2);
                $this->world->pop();
                $this->world->push($this->test3);
                $results = $this->world->run();

                Preview::$config = $this->test_config;

                $case_total = 0;
                foreach($results as $suite) {
                    $case_total += count($suite->cases());
                }

                ok(count($results) == 2);
                ok($case_total == 1);
            });
        });
    });
});
