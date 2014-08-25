<?php

namespace TraceAnalyzer\Test;

use TraceAnalyzer\Builder;
use TraceAnalyzer\Record\Entry;
use TraceAnalyzer\Record\Leave;
use TraceAnalyzer\Record\End;

use TraceAnalyzer\Test\CollectorReport;

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

    public function traceEnd(\DateTime $end)
    {
        $this->report->end = $end;
    }


    /**
     * @return TraceAnalyzer\Report
     */
    public function build()
    {
        return $this->report;
    }
}
