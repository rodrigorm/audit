<?php

namespace RodrigoRM\Audit\Test;

use RodrigoRM\Audit\Report;

class CollectorReport implements Report
{
    public $version;
    public $format;
    public $start;
    public $end;
    public $records = array();

    public function write($filename)
    {
    }
}

