<?php

namespace RodrigoRM\Audit\Record;

use RodrigoRM\Audit\Record;

class Result implements Record
{
    private $value;

    public function __construct($value)
    {
        $this->value = $value;
    }
}
