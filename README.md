TimeRange <a href="https://travis-ci.org/jarnstedt/TimeRange" target="_blank"><img src="https://travis-ci.org/jarnstedt/TimeRange.png" /></a> [![SensioLabsInsight](https://insight.sensiolabs.com/projects/7c76a5b0-806e-4e08-8848-fd1853617ad1/mini.png)](https://insight.sensiolabs.com/projects/7c76a5b0-806e-4e08-8848-fd1853617ad1)
=========

Compare and loop time ranges in PHP.

## Install

For manual install copy timerange.php to your project.

Install with Composer:
```
composer.phar require jarnstedt/timerange
```
## Examples

### Creating a TimeRange object
```php
use TimeRange;

// Create a new TimeRange
$timeRange1 = new TimeRange('2013-03-31 00:00:00', '2013-04-01 01:09:00');

// You can also use PHP DateTime objects
$start = new DateTime('2000-01-01');
$end = new DateTime('2000-01-05');
$timeRange2 = new TimeRange($start, $end);
```

### Looping TimeRange
TimeRange supports looping minutes, hours, days and months.
```php
// Echo all days in time range
foreach ($timeRange1->getDays() as $datetime) {
  echo $datetime->format('Y-m-d')."<br>";
}

// Echo every other day in time range backwards
foreach ($timeRange1->getDays(2, TimeRange::BACKWARD) as $datetime) {
  echo $datetime->format('Y-m-d')."<br>";
}

// Echo all minutes
foreach ($timeRange1->getMinutes() as $datetime) {
  echo $datetime->format('Y-m-d H:i')."<br>";
}
```

### Check if two TimeRanges overlap
```php
$timeRange1 = new TimeRange('2013-03-31 00:00', '2013-04-01 01:09');
$timeRange2 = new TimeRange('2013-01-01 12:30', '2013-01-05 14:00');

if ($timeRange1->overlaps($timeRange2)) {
  echo "Ranges overlap";
}

// Check if date overlaps the TimeRange
if ($timeRange1->overlaps("2013-04-01", TimeRange::DAY)) {
  ...
}
```

### Changing start or end of the range
```php
$timeRange = new TimeRange('2013-03-31 00:00', '2013-04-01 01:09');
$timeRange->setStart('2013-02-28');
$timeRange->setEnd('2013-03-03');
```

## Running unit tests
Download phpunit from http://phpunit.de/ and in project directory run:
```
php phpunit.phar
```
