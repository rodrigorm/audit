<?php

namespace TraceAnalyzer;

use TraceAnalyzer\Test\CollectorBuilder;
use TraceAnalyzer\Record\Entry;
use TraceAnalyzer\Record\Leave;
use TraceAnalyzer\Record\End;

class ReaderTest extends \PHPUnit_Framework_TestCase
{
    private $builder;
    private $reader;

    protected function setUp()
    {
        $this->givenACollectorBuilder();
    }

    public function testShouldParseCorrectFile()
    {
        $this->whenIReadATraceFile('trace.xt');
        $this->assertRecords(41);
    }

    public function testShouldParseAEntryRecord()
    {
        $this->whenIReadATraceFile('trace.xt');
        $this->assertRecordAt(0, new Entry(
            1,
            0,
            0.000239,
            249600,
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
        $this->assertRecordAt(5, new Leave(5, 4, 0.000359, 250528));
    }

    public function testShouldParseAEndRecord()
    {
        $this->whenIReadATraceFile('trace.xt');
        $this->assertRecordAt(40, new End(15.002310, 8240));
    }

    public function testShouldFailsWhenInvalidRecordFound()
    {
        $this->setExpectedException('TraceAnalyzer\InvalidRecordException');
        $this->whenIReadATraceFile('invalid_trace.xt');
    }

    private function givenACollectorBuilder()
    {
        $this->builder = new CollectorBuilder();
        $this->reader = new Reader($this->builder);
    }

    private function whenIReadATraceFile($file)
    {
        $this->reader->read(__DIR__ . '/../fixtures/' . $file);
    }

    private function assertRecords($expected)
    {
        $this->assertCount($expected, $this->builder->records);
    }

    private function assertRecordAt($index, $expected)
    {
        $this->assertEquals($expected, $this->builder->records[$index]);
    }
}
