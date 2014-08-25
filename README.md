# Audit

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

*Generating tracing reports from file /tmp/7VGvko.xt:*
  *Class Diagram ... done*


Time: 2.51 seconds, Memory: 7.25Mb

OK (10 tests, 10 assertions)
```

# Generated Reports

## Class Diagram

A class diagram will be generated at `build/logs/class_diagram.dot`, you could use the graphviz package to view the file.

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
