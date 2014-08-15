<?php

namespace TraceAnalyzer\Test;

use TraceAnalyzer\Builder;
use TraceAnalyzer\Record\Entry;
use TraceAnalyzer\Record\Leave;
use TraceAnalyzer\Record\End;

class CollectorBuilder implements Builder
{
    public $version;
    public $format;
    public $start;
    public $end;
    public $records = array();

    public function setVersion($version)
    {
        $this->version = $version;
    }

    public function setFileFormat($format)
    {
        $this->format = $format;
    }

    public function traceStart(\DateTime $start)
    {
        $this->start = $start;
    }

    public function addEntryRecord(Entry $record)
    {
        $this->records[] = $record;
    }

    public function addLeaveRecord(Leave $record)
    {
        $this->records[] = $record;
    }

    public function addEndRecord(End $record)
    {
        $this->records[] = $record;
    }

    public function traceEnd(\DateTime $end)
    {
        $this->end = $end;
    }
}
