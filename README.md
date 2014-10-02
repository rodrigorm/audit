# Audit

[![Code Climate](https://codeclimate.com/github/rodrigorm/audit/badges/gpa.svg)](https://codeclimate.com/github/rodrigorm/audit)
[![Test Coverage](https://codeclimate.com/github/rodrigorm/audit/badges/coverage.svg)](https://codeclimate.com/github/rodrigorm/audit)
[![SensioLabsInsight](https://insight.sensiolabs.com/projects/77693103-78a9-4dea-a7a7-fb7935f0934d/mini.png)](https://insight.sensiolabs.com/projects/77693103-78a9-4dea-a7a7-fb7935f0934d)

Uses xdebug function trace to generate code execution reports.

# Usage

Add the listerner to phpunit.xml

```xml
  <listeners>
      <listener class="RodrigoRM\Audit\PHPUnit\TestListener">
          <arguments>
              <string>RodrigoRM\Audit\</string>
          </arguments>
      </listener>
  </listeners>
```

Run the tests:

```
$ phpunit
```

You should see the output:

```
PHPUnit 4.1.6 by Sebastian Bergmann.

Configuration read from /home/rodrigomoyle/workspace/xdebug-trace-analyzer/phpunit.xml.dist

..........

Generating tracing reports from file /tmp/7VGvko.xt:
  Class Diagram ... done


Time: 2.51 seconds, Memory: 7.25Mb

OK (10 tests, 10 assertions)
```

# Generated Reports

## Class Diagram

A class diagram will be generated at `build/logs/class\_diagram.dot`.

You could use the graphviz package to view the file:

```
$ dot -Txlib player_state.dot
```

# Tools

## Class Diagram

Uses to generate a class diagram from any trace file, usage:

```
$ bin/class-diagram Namespace /tmp/trace.1234.xt
```

## License

Copyright (C) 2014 Rodrigo Moyle <rodrigorm@gmail.com>

This program is free software: you can redistribute it and/or modify
it under the terms of the Lesser GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.

You should have received a copy of the Lesser GNU General Public License
along with this program. If not, see http://www.gnu.org/licenses/.
