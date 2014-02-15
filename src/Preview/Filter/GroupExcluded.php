<?php

namespace Preview\Filter;

class GroupExcluded extends GroupIncluded {
    public function __construct($groups) {
        $this->groups = $groups;
    }

    protected function matched($test) {
        foreach ($this->groups as $group) {
            if ($test->in_group($group)) {
                return false;
            }
        }
        return true;
    }

    protected function filter($suite) {
        if (!$this->matched($suite)) {
            return false;
        }

        return $this->filter_children($suite);
    }
}