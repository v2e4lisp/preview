<?php
namespace Preview;

class Timer {
    private $start_point = null;

    private $stop_point = null;

    public function start() {
        if (!$this->started()) {
            $this->start_point = microtime(true);
        }
    }

    public function stop() {
        if ($this->running()) {
            $this->stop_point = microtime(true);
        }
    }

    public function started() {
        return !empty($this->start_point);
    }

    public function stopped() {
        return !empty($this->stop_point);
    }

    public function running() {
        return $this->started() and !$this->stopped();
    }

    public function span() {
        if ($this->stopped()) {
            return $this->stop_point - $this->start_point;
        }
    }
}