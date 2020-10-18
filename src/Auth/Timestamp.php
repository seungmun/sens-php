<?php

namespace Sens\Auth;

class Timestamp
{
    /**
     * The current timestamp.
     *
     * @var string
     */
    protected $timestamp;

    /**
     * Create a new timestamp instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->timestamp = $this->makeTimestamp();
    }

    /**
     * Make current timestamp string.
     *
     * @return string
     */
    protected function makeTimestamp()
    {
        return strval((int) round(microtime(true) * 1000));
    }

    /**
     * Get the current generated timestamp.
     *
     * @return string
     */
    public function getTimestamp()
    {
        return $this->timestamp;
    }

    /**
     * The __toString method allows a class to decide how it will react when it is converted to a string.
     *
     * @return string
     */
    public function __toString()
    {
        return $this->getTimestamp();
    }
}
