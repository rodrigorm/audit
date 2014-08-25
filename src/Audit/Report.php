<?php

namespace RodrigoRM\Audit;

interface Report
{
    public function write($filename);
}
