<?php

namespace TraceAnalyzer\Record;

use \TraceAnalyzer\Record;

class End implements Record
{
    private $time;
    private $memory;

    public function __construct($time, $memory)
    {
        $this->time = $time;
        $this->memory = $memory;
    }
}
