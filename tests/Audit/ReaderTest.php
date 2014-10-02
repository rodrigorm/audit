<?php

namespace RodrigoRM\Audit;

use RodrigoRM\Audit\Test\CollectorReport;
use RodrigoRM\Audit\Test\CollectorBuilder;
use RodrigoRM\Audit\Record\Entry;
use RodrigoRM\Audit\Record\Leave;
use RodrigoRM\Audit\Record\End;
use RodrigoRM\Audit\Record\Result;

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
        $this->assertRecords(64);
    }

    public function testShouldParseAnEntryRecord()
    {
        $this->whenIReadATraceFile('trace.xt');
        $this->assertRecordAt(0, new Entry(
            1,
            0,
            0.000175,
            240864,
            '{main}',
            1,
            '',
            '/home/rmoyle/workspace/audit/tests/fixtures/main.php',
            0
        ));
    }

    public function testShouldParseAnEntryRecordWithParams()
    {
        $this->whenIReadATraceFile('trace.xt');
        $this->assertRecordAt(2, new Entry(
            3,
            2,
            00.000268,
            241648,
            'Calculatrice->calculDivers',
            1,
            '',
            '/home/rmoyle/workspace/audit/tests/fixtures/main.php',
            46,
            array('long', 'long')
        ));
    }

    public function testShouldParseALeaveRecord()
    {
        $this->whenIReadATraceFile('trace.xt');
        $this->assertRecordAt(6, new Leave(6, 5, 0.000380, 242296));
    }

    public function testShouldParseAEndRecord()
    {
        $this->whenIReadATraceFile('trace.xt');
        $this->assertRecordAt(63, new End(15.002602, 8240));
    }

    public function testShouldParseAReturnRecord()
    {
        $this->whenIReadATraceFile('trace.xt');
        $this->assertRecordAt(7, new Result('array(2)'));
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
