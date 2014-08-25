<?php

namespace RodrigoRM\Audit\Record;

use \RodrigoRM\Audit\Record;

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
