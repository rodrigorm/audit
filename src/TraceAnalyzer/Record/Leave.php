<?php

namespace TraceAnalyzer\Record;

use TraceAnalyzer\Record;

class Leave implements Record
{
    private $level;
    private $stack;
    private $time;
    private $memory;

    public function __construct($level, $stack, $time, $memory)
    {
        $this->level = $level;
        $this->stack = $stack;
        $this->time = $time;
        $this->memory = $memory;
    }
}
