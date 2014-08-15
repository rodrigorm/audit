<?php

namespace TraceAnalyzer;

use TraceAnalyzer\Record\Entry;
use TraceAnalyzer\Record\Leave;
use TraceAnalyzer\Record\End;

interface Builder
{
    public function setVersion($version);
    public function setFileFormat($format);
    public function traceStart(\DateTime $start);
    public function addEntryRecord(Entry $record);
    public function addLeaveRecord(Leave $record);
    public function addEndRecord(End $record);
    public function traceEnd(\DateTime $end);
}
