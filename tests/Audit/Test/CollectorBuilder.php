<?php

namespace RodrigoRM\Audit\Test;

use RodrigoRM\Audit\Builder;
use RodrigoRM\Audit\Record\Entry;
use RodrigoRM\Audit\Record\Leave;
use RodrigoRM\Audit\Record\End;
use RodrigoRM\Audit\Record\Result;

use RodrigoRM\Audit\Test\CollectorReport;

class CollectorBuilder implements Builder
{
    public function __construct(CollectorReport $report)
    {
        $this->report = $report;
    }

    public function setVersion($version)
    {
        $this->report->version = $version;
    }

    public function setFileFormat($format)
    {
        $this->report->format = $format;
    }

    public function traceStart(\DateTime $start)
    {
        $this->report->start = $start;
    }

    public function addEntryRecord(Entry $record)
    {
        $this->report->records[] = $record;
    }

    public function addLeaveRecord(Leave $record)
    {
        $this->report->records[] = $record;
    }

    public function addEndRecord(End $record)
    {
        $this->report->records[] = $record;
    }

    public function addReturnRecord(Result $record)
    {
        $this->report->records[] = $record;
    }

    public function traceEnd(\DateTime $end)
    {
        $this->report->end = $end;
    }


    /**
     * @return RodrigoRM\Audit\Report
     */
    public function build()
    {
        return $this->report;
    }
}
