<?php
namespace Preview\DSL\BDD;

use Preview\Preview;
use Preview\Core\TestSuite;
use Preview\Core\TestCase;

describe("TestSuite", function () {
    subject(function () {
        return new TestSuite("test suite", function () {});
    });

    describe("#force_error", function () {
        before_each(function () {
            $this->subject->add(new TestSuite("", function(){}));
            $this->subject->add(new TestCase("", function(){}));
            $this->subject->force_error(new \ErrorException("error"));
        });

        it_behaves_like("error test");

        describe("children cases", function () {
            subject(function () {
                return $this->subject->cases[0];
            });

            it_behaves_like("error test");
        });

        describe("children suites", function () {
            subject(function () {
                return $this->subject->suites[0];
            });

            it_behaves_like("error test");
        });
    });

    describe("#set_parent", function () {
        it("set parent test suite", function () {
            $parent = new TestSuite("parent", function() {});
            $this->subject->set_parent($parent);

            ok($this->subject->parent == $parent);
            ok($parent->suites == array($this->subject));
        });
    });

    describe("#add", function () {
        it("add child test suite", function () {
            $parent = new TestSuite("parent", function() {});
            $parent->add($this->subject);

            ok($this->subject->parent == $parent);
            ok($parent->suites == array($this->subject));
        });

        it("add child test suite", function () {
            $case = new TestCase("child case", function() {});
            $this->subject->add($case);

            ok($case->parent == $this->subject);
            ok($this->subject->cases == array($case));
        });
    });

    describe("#add_to_group", function () {
        before_each(function () {
            $this->subject->add(new TestSuite("child suite", function(){}));
            $this->subject->add(new TestCase("child case", function(){}));
            $this->subject->set_parent(new TestSuite("parent", function () {}));
            $this->group = "sample group";
            $this->subject->add_to_group($this->group);
        });

        it("add itself to group", function () {
            ok($this->subject->in_group($this->group));
        });

        it("add its parent suite to group", function () {
            ok($this->subject->parent->in_group($this->group));
        });

        it("add its children suites to group", function () {
            ok($this->subject->suites[0]->in_group($this->group));
        });

        it("add its children cases to group", function () {
            ok($this->subject->cases[0]->in_group($this->group));
        });
    });

    describe("#remove", function () {
        before_each(function () {
            $this->child_suite = new TestSuite("child suite", function(){});
            $this->child_case = new TestCase("child case", function(){});

            $this->subject->add($this->child_suite);
            $this->subject->add($this->child_case);
        });

        it("should be able to remove its child test suite", function () {
            $this->subject->remove($this->child_suite);
            ok(empty($this->subject->suites));
        });

        it("should be able to remove its child test case", function () {
            $this->subject->remove($this->child_case);
            ok(empty($this->subject->cases));
        });
    });
});