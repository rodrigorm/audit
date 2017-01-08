<?php

namespace RodrigoRM\Audit\Test;

use RodrigoRM\Audit\Reader;
use RodrigoRM\Audit\Test\CollectorReport;
use RodrigoRM\Audit\Test\CollectorBuilder;
use RodrigoRM\Audit\Record\Entry;
use RodrigoRM\Audit\Record\Leave;
use RodrigoRM\Audit\Record\End;

class ReaderTest extends \PHPUnit_Framework_TestCase
{
    private $report;
    private $builder;
    private $reader;

    protected function setUp()
    {
        $this->givenACollectorBuilder();
    }

    public function testShouldParseCorrectFile()
    {
        $this->whenIReadATraceFile('trace.xt');
        $this->assertRecords(43);
    }

    public function testShouldParseAEntryRecord()
    {
        $this->whenIReadATraceFile('trace.xt');
        $this->assertRecordAt(0, new Entry(
            1,
            0,
            0.000204,
            247904,
            '{main}',
            1,
            '',
            '/home/rodrigomoyle/workspace/xdebug-trace-analyzer/tests/fixtures/main.php',
            0
        ));
    }

    public function testShouldParseALeaveRecord()
    {
        $this->whenIReadATraceFile('trace.xt');
        $this->assertRecordAt(6, new Leave(6, 5, 0.000321, 248768));
    }

    public function testShouldParseAEndRecord()
    {
        $this->whenIReadATraceFile('trace.xt');
        $this->assertRecordAt(42, new End(15.001861, 8240));
    }

    public function testShouldFailsWhenInvalidRecordFound()
    {
        $this->setExpectedException('RodrigoRM\Audit\InvalidRecordException');
        $this->whenIReadATraceFile('invalid_trace.xt');
    }

    private function givenACollectorBuilder()
    {
        $this->report = new CollectorReport();
        $this->builder = new CollectorBuilder($this->report);
        $this->reader = new Reader($this->builder);
    }

    private function whenIReadATraceFile($file)
    {
        $this->reader->read(__DIR__ . '/../fixtures/' . $file);
    }

    private function assertRecords($expected)
    {
        $this->assertCount($expected, $this->report->records);
    }

    private function assertRecordAt($index, $expected)
    {
        $this->assertEquals($expected, $this->report->records[$index]);
    }
}
