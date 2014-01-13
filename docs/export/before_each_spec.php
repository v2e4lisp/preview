<?php
/*
 * How to use before_each hook
 */

namespace Preview\DSL\Export;

require_once __DIR__.'/../ok.php';

$suite = array(
    "before each" => function () {
        $this->usage = "run before each test case.";

        $this->ref = new \stdClass;
        $this->ref->name = "wenjun.yan";
        $this->value = "string";
    },

    "test case have access to vars defined ".
    "in before each hook" => function () {
        ok($this->usage);

        ok($this->ref->name);
        ok($this->value);

        $this->ref->name = null;
        $this->value = null;
    },

    "before hook only run once" => function () {
        // ref and value are reassigned.
        ok($this->ref->name); // object is passed by "ref"
        ok($this->value); // string is passed by value.
    },
);

export("[before each]", $suite);
