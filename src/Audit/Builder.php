<?php

namespace RodrigoRM\Audit;

use RodrigoRM\Audit\Record\Entry;
use RodrigoRM\Audit\Record\Leave;
use RodrigoRM\Audit\Record\End;
use RodrigoRM\Audit\Record\Result;

interface Builder
{
    public function setVersion($version);
    public function setFileFormat($format);
    public function traceStart(\DateTime $start);
    public function addEntryRecord(Entry $record);
    public function addLeaveRecord(Leave $record);
    public function addReturnRecord(Result $record);
    public function addEndRecord(End $record);
    public function traceEnd(\DateTime $end);

    /**
     * @return RodrigoRM\Audit\Report
     */
    public function build();
}
