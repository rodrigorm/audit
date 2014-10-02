<?php

namespace RodrigoRM\Audit;

use RodrigoRM\Audit\Builder;
use RodrigoRM\Audit\Record\Entry;
use RodrigoRM\Audit\Record\Leave;
use RodrigoRM\Audit\Record\End;
use RodrigoRM\Audit\Record\Result;
use RodrigoRM\Audit\InvalidRecordException;

class Reader
{
    const DATETIME_FORMAT = 'Y-m-d G:i:s';

    const RECORD_DELIMITER = "\t";

    const RECORD_FIELD_LEVEL = 0;
    const RECORD_FIELD_STACK = 1;
    const RECORD_FIELD_TYPE = 2;
    const RECORD_FIELD_TIME = 3;
    const RECORD_FIELD_MEMORY = 4;
    const RECORD_FIELD_FUNCTION = 5;
    const RECORD_FIELD_RETURN_VALUE = 5;
    const RECORD_FIELD_USER_DEFINED = 6;
    const RECORD_FIELD_INCLUDE = 7;
    const RECORD_FIELD_FILENAME = 8;
    const RECORD_FIELD_LINE = 9;
    const RECORD_FIELD_PARAMETERS = 11;

    const RECORD_TYPE_ENTRY = '0';
    const RECORD_TYPE_EXIT  = '1';
    const RECORD_TYPE_END  = '';
    const RECORD_TYPE_RETURN  = 'R';

    private $builder;

    public function __construct(Builder $builder)
    {
        $this->builder = $builder;
    }

    public function read($filename)
    {
        $file = fopen($filename, 'r');
        $this->parse($file);
    }

    private function parse($file)
    {
        $line = 1;
        while (!feof($file)) {
            $this->parseRow(trim(fgets($file), "\n"), $line++);
        }
    }

    private function parseRow($row, $line)
    {
        if (preg_match('@^Version: (.*)@', $row, $matches)) {
            $this->builder->setVersion($matches[1]);
        } elseif (preg_match('@^File format: (.*)@', $row, $matches)) {
            $this->builder->setFileFormat($matches[1]);
        } elseif (preg_match('@^TRACE\s+(START|END)\s+\[(.*)\]@', $row, $matches)) {
            $date = \DateTime::createFromFormat(self::DATETIME_FORMAT, $matches[2]);
            if ($matches[1] == 'START') {
                $this->builder->traceStart($date);
            } else {
                $this->builder->traceEnd($date);
            }
        } elseif (trim($row) != '') {
            $this->parseRecord($row, $line);
        }
    }

    private function parseRecord($row, $line)
    {
        $values = explode(self::RECORD_DELIMITER, $row);
        $type = $values[self::RECORD_FIELD_TYPE];

        if ($type == self::RECORD_TYPE_ENTRY) {
            $arguments = array_slice($values, self::RECORD_FIELD_PARAMETERS);

            return $this->builder->addEntryRecord(new Entry(
                $values[self::RECORD_FIELD_LEVEL],
                $values[self::RECORD_FIELD_STACK],
                $values[self::RECORD_FIELD_TIME],
                $values[self::RECORD_FIELD_MEMORY],
                $values[self::RECORD_FIELD_FUNCTION],
                $values[self::RECORD_FIELD_USER_DEFINED],
                $values[self::RECORD_FIELD_INCLUDE],
                $values[self::RECORD_FIELD_FILENAME],
                $values[self::RECORD_FIELD_LINE],
                $arguments
            ));
        } elseif ($type == self::RECORD_TYPE_EXIT) {
            return $this->builder->addLeaveRecord(new Leave(
                $values[self::RECORD_FIELD_LEVEL],
                $values[self::RECORD_FIELD_STACK],
                $values[self::RECORD_FIELD_TIME],
                $values[self::RECORD_FIELD_MEMORY]
            ));
        } elseif ($type == self::RECORD_TYPE_END) {
            return $this->builder->addEndRecord(new End(
                $values[self::RECORD_FIELD_TIME],
                $values[self::RECORD_FIELD_MEMORY]
            ));
        } elseif ($type == self::RECORD_TYPE_RETURN) {
            return $this->builder->addReturnRecord(new Result(
                $values[self::RECORD_FIELD_RETURN_VALUE]
            ));
        }

        throw new InvalidRecordException(sprintf('Invalid record found at line %s', $line));
    }
}
