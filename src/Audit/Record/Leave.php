<?php

namespace RodrigoRM\Audit\Record;

use RodrigoRM\Audit\Record;

class Leave implements Record
{
    private $depth;
    private $stack;
    private $time;
    private $memory;

    public function __construct($depth, $stack, $time, $memory)
    {
        $this->depth = $depth;
        $this->stack = $stack;
        $this->time = $time;
        $this->memory = $memory;
    }
}
