<?php

namespace TraceAnalyzer;

interface Report
{
    public function write($filename);
}
