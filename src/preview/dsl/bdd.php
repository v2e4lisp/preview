<?php
/**
 * bdd style dsl
 *
 * @package Preview
 * @author Wenjun Yan
 * @email mylastnameisyan@gmail.com
 */

namespace Preview\DSL\BDD;

use \Preview\Preview;
use \Preview\Core\TestSuite;
use \Preview\Core\TestCase;
use \Preview\Core\TestShared;
use \Preview\DSL\TestAPI;

/**
 * Start a test suite.
 *
 * @param string $title A string to describe this test suite.
 * @param function $fn Default is null(which means pending).
 * @return object TestSuite object
 */
function describe($title, $fn=null) {
    $desc = new TestSuite($title, $fn);
    $desc->set_parent(Preview::$world->current());
    Preview::$world->push($desc);
    $desc->setup();
    Preview::$world->pop();
    return new TestAPI($desc);
}

/**
 * An alias for describe
 *
 * @param string $title A string to describe a certain situation,
 *                      typically starts with 'when'.
 * @param function $fn Default is null(which means pending).
 * @return object TestSuite object.
 */
function context($title, $fn=null) {
    return describe($title, $fn);
}

/**
 * Start a test case.
 *
 * @param string $title A string to describe this test case.
 * @param function $fn Default is null(which means pending).
 * @return object TestCase object.
 */
function it($title, $fn=null) {
    $case = new TestCase($title, $fn);
    Preview::$world->current()->add($case);
    return new TestAPI($case);
}

/**
 * Assign value to var.
 * This is shorthand for assignment in before each hook.
 *
 * before_each(function () {
 *     $this->somevar = value
 * })
 *
 * let("somevar", value);
 *
 * If value is an object it will first be cloned and the copy
 * will be assigned to $name for every case.
 * And if it's an closure it will be invoked and the return value
 * will be used.
 *
 * @param string $name
 * @param mixed $value
 * @retrun null
 */

function let($name, $value) {
    if (Preview::$config->use_implicit_context) {
        before_each(function () use ($name, $value) {
            if ($value instanceof \Closure) {
                $value = $value->bindTo($this, $this)->__invoke();
            } else if (is_object($value)) {
                $value = clone $value;
            }
            $this->$name = $value;
        });
    } else {
        before_each(function ($self) use ($name, $value) {
            if ($value instanceof \Closure) {
                $value = $value->__invoke($self);
            } else if (is_object($value)) {
                $value = clone $value;
            }
            $self->$name = $value;
        });
    }
}

/**
 * Assign value to subject
 * It is short hand for let("subject", $value);
 *
 * @param mixed $value
 * @retrun null
 */
function subject($value) {
    let("subject", $value);
}

/**
 * Current test suite first run this hook before any test cases/suites.
 *
 * @param function $fn
 * @retrun null
 */
function before($fn) {
    Preview::$world->current()->before($fn);
}

/**
 * Current test suite run this hook after all test cases/suites.
 *
 * @param function $fn
 * @retrun null
 */
function after($fn) {
    Preview::$world->current()->after($fn);
}

/**
 * A hook to be called before running each test case in current test suite.
 *
 * @param function $fn
 * @retrun null
 */
function before_each($fn) {
    Preview::$world->current()->before_each($fn);
}

/**
 * A hook to be called after running each test case in current test suite.
 *
 * @param string $param
 * @retrun null
 */
function after_each($fn) {
    Preview::$world->current()->before_after($fn);
}

/**
 * Create a shared test
 * Shared test when applied (by invoking it_behaves_like)
 * will use current test's context object as its context.
 *
 * @param string $name
 * @param function $fn
 * @retrun null
 */
function shared_example($name, $fn) {
    Preview::$world->add_shared_example(new TestShared($name, $fn));
}

/**
 * Invoke a shared test
 *
 * @param string $name
 * @retrun null
 */
function it_behaves_like($name) {
    $args = func_get_args();
    array_shift($args);
    $shared = Preview::$world->shared_example($name);
    if (!$shared) {
        throw new \Exception("no such shared test: $name");
    }
    $shared->setup($args);
}