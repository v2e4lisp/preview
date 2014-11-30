<?php
/**
 * Timer class
 *
 * @pakcage Preview
 * @author Wenjun Yan
 * @email mylastnameisyan@gmail.com
 */

namespace Preview;

class Timer {
    /**
     * start time
     *
     * @var float|null $start_point
     */
    private $start_point = null;

    /**
     * stop time
     *
     * @var float|null $stop_point
     */
    private $stop_point = null;

    /**
     * start timer if it's not been started yet.
     *
     * @param null
     * @return false|float return current time if success
     */
    public function start() {
        if (!$this->started()) {
            $this->start_point = microtime(true);
            return $this->start_point;
        }
        return false;
    }

    /**
     * Stop timer if it's running.
     *
     * @param null
     * @return false|float return current time if success
     */
    public function stop() {
        if ($this->running()) {
            $this->stop_point = microtime(true);
            return $this->stop_point;
        }
        return false;
    }

    /**
     * check if timer is started
     *
     * @param null
     * @return bool
     */
    public function started() {
        return !empty($this->start_point);
    }

    /**
     * check if timer is stooped
     *
     * @param null
     * @return bool
     */
    public function stopped() {
        return !empty($this->stop_point);
    }

    /**
     * check timer is running
     *
     * @param null
     * @return bool
     */
    public function running() {
        return $this->started() and !$this->stopped();
    }

    /**
     * Get time span
     *
     * @param null
     * @return null|float time span
     */
    public function span() {
        if ($this->stopped()) {
            return $this->stop_point - $this->start_point;
        }
    }
}
