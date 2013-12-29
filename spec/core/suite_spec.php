<?php
namespace Preview\DSL\BDD;

use Preview\Preview;
use Preview\Core\TestSuite;
use Preview\Core\TestCase;
use Preview\Reporter\Base as BaseReporter;

describe("TestSuite", function () {
    subject(function () {
        return new TestSuite("test suite", function () {});
    });

    it_behaves_like("groupable test");

    context("when skipped", function () {
        before_each(function () {
            $this->subject->add(new TestSuite("", function(){}));
            $this->subject->add(new TestCase("", function(){}));
        });

        before_each(function () {
            $this->subject->skip();
        });

        it_behaves_like("skipped test");

        describe("children cases", function () {
            subject(function () {
                return $this->subject->cases[0];
            });

            it_behaves_like("skipped test");
        });

        describe("children suites", function () {
            subject(function () {
                return $this->subject->suites[0];
            });

            it_behaves_like("skipped test");
        });
    });

    context("when pending", function () {
        subject(new TestSuite("test suite", null));
        it_behaves_like("pending test");
    });

    context("when finished", function () {
        before_each(function () {
            $old_reporter = Preview::$config->reporter;
            Preview::$config->reporter = new BaseReporter;
            $this->subject->run();
            Preview::$config->reporter = $old_reporter;
        });

        it_behaves_like("finished test");
    });

    it_behaves_like("having its own hooks to run", "after");
    it_behaves_like("having its own hooks to run", "before");

    context("with parent", function () {
        let("parent", function () {
            $parent = new TestSuite("parent suite", function () {});
            $parent->add($this->subject);
            return $parent;
        });
        it_behaves_like("having hooks for its children", "after_each");
        it_behaves_like("having hooks for its children", "before_each");
    });
});