<?php
namespace Ikirouta;

use \Ikirouta\TimeRange;
use \DateTime;

/**
 * Tests for TimeRange class.
 * 
 * @package TimeRange
 * @author  Joonas Järnstedt <joonas@xnetti.net>
 */
class TestTimeRange extends \PHPUnit_Framework_TestCase
{

    /**
     * Try creating NULL timerange.
     * 
     * @expectedException InvalidArgumentException
     */
    public function testCreateNull()
    {
        $timerange = new TimeRange('2000-01-01', null);
    }

    /**
     * Create TimeRange objects from DateTime objects.
     */
    public function testCreateFromDateTime()
    {
        $start1 = new DateTime('2000-01-01');
        $end1 = new DateTime('2000-01-01');
        $start2 = new DateTime('2000-01-01 12:30');
        $end2 = new DateTime('2000-01-01 14:00');
        $start3 = new DateTime('2013-01-01 00:00:00');
        $end3 = new DateTime('2013-01-20 00:00:01');

        $timeRange1 = new TimeRange($start1, $end1);
        $timeRange2 = new TimeRange($start2, $end2);
        $timeRange3 = new TimeRange($start3, $end3);

        $this->assertInstanceOf('Ikirouta\TimeRange', $timeRange1);
    }

    /**
     * Create TimeRange from datetime strings.
     */
    public function testCreateFromString()
    {
        $timeRange1 = new TimeRange('2013-01-01', '2013-01-01');
        $timeRange2 = new TimeRange('1900-01-01', '2020-01-01');
        $timeRange3 = new TimeRange('2013-03-20 01:02:30', '2013-12-31 23:59:59');
        $this->assertInstanceOf('Ikirouta\TimeRange', $timeRange1);
    }

    /**
     * Try creating invalid TimeRange from DateTime objects.
     * 
     * @expectedException InvalidArgumentException
     */
    public function testInvalidCreateFromDateTime1()
    {
        $start = new DateTime('2000-01-01');
        $end = new DateTime('2000-01-01 23:59:59');

        // End time before start exceptions
        $timerange = new TimeRange($end, $start);

    }

    /**
     * Try creating invalid TimeRange from DateTime objects.
     * 
     * @expectedException InvalidArgumentException
     */
    public function testInvalidCreateFromDateTime2()
    {
        $start = new DateTime('2013-01-01 12:30');
        $end = new DateTime('2013-01-01 14:00');
        $timerange = new TimeRange($end, $start);
    }

    /**
     * Try creating invalid TimeRange from DateTime objects.
     * 
     * @expectedException InvalidArgumentException
     */
    public function testInvalidCreateFromDateTime3()
    {
        $start = new DateTime('2013-12-01');
        $end = new DateTime('2013-12-02');
        $timerange = new TimeRange($end, $start);
    }

    /**
     * Try creating invalid TimeRanges from datetime strings.
     * 
     * @expectedException InvalidArgumentException
     */
    public function testInvalidCreateFromString1()
    {
        // End time before start exceptions
        $timerange = new TimeRange('2013-01-01 00:00:01', '2013-01-01 00:00:00');
    }

    /**
     * Test getDays() function.
     */
    public function testGetDays()
    {
        $timerange = new TimeRange('2013-01-01 13:30:49', '2013-01-31 23:59:59');

        $days = $timerange->getDays(1, TimeRange::BACKWARD);
        $days2 = $timerange->getDays();
        $days3 = $timerange->getDays(2);

        $this->assertEquals(31, count($days));
        $this->assertEquals(31, count($days2));
        $this->assertEquals(16, count($days3));

        foreach ($days as $day) {
            $time = $day->format('H:i:s');
            $this->assertEquals('00:00:00', $time);
        }

        foreach ($days2 as $day) {
            $time = $day->format('H:i:s');
            $this->assertEquals('00:00:00', $time);
        }

        foreach ($days3 as $day) {
            $time = $day->format('H:i:s');
            $this->assertEquals('00:00:00', $time);
        }
    }

    /**
     * Test getDays() function with a long time range.
     */
    public function testGetDaysMany()
    {
        $timerange = new TimeRange('2012-01-01 00:00:00', '2012-12-31 23:59:59');
        $days = $timerange->getDays();
        $days2 = $timerange->getDays(1, TimeRange::BACKWARD);
        // 2012 is leap year --> 366 days
        $this->assertEquals(366, count($days));
        foreach ($days as $day) {
            $time = $day->format('H:i:s');
            $this->assertEquals('00:00:00', $time);
        }
        $this->assertEquals(366, count($days2));
    }

    /**
     * Test getMonths() function.
     */
    public function testGetMonths()
    {
        $timerange = new TimeRange('2013-01-01 13:30:49', '2013-12-31 23:59:59');

        $months = $timerange->getMonths(1, TimeRange::BACKWARD);
        $months2 = $timerange->getMonths();
        $months3 = $timerange->getMonths(2);

        $this->assertEquals(12, count($months));
        $this->assertEquals(12, count($months2));
        $this->assertEquals(6, count($months3));

        foreach ($months as $month) {
            $time = $month->format('d H:i:s');
            $this->assertEquals('01 00:00:00', $time);
        }

        foreach ($months2 as $month) {
            $time = $month->format('d H:i:s');
            $this->assertEquals('01 00:00:00', $time);
        }

        foreach ($months3 as $month) {
            $time = $month->format('d H:i:s');
            $this->assertEquals('01 00:00:00', $time);
        }
    }

    /**
     * Test getHours() function.
     */
    public function testGetHours()
    {
        $timerange = new TimeRange('2013-01-01 00:59:59', '2013-01-02 00:59:59');

        $hours = $timerange->getHours(1, TimeRange::BACKWARD);
        $hours2 = $timerange->getHours();
        $hours3 = $timerange->getHours(2);

        $this->assertEquals(25, count($hours));
        $this->assertEquals(25, count($hours2));
        $this->assertEquals(13, count($hours3));

        foreach ($hours as $hour) {
            $time = $hour->format('is');
            $this->assertEquals('0000', $time);
        }

        foreach ($hours2 as $hour) {
            $time = $hour->format('is');
            $this->assertEquals('0000', $time);
        }

        foreach ($hours3 as $hour) {
            $time = $hour->format('is');
            $this->assertEquals('0000', $time);
        }
    }

    /**
     * Test getMinutes() function.
     */
    public function testGetMinutes()
    {
        $timerange = new TimeRange('2013-01-01 23:00:59', '2013-01-02 00:00:59');

        $minutes = $timerange->getMinutes(1, TimeRange::BACKWARD);
        $minutes2 = $timerange->getMinutes();
        $minutes3 = $timerange->getMinutes(2);

        $this->assertEquals(61, count($minutes));
        $this->assertEquals(61, count($minutes2));
        $this->assertEquals(31, count($minutes3));

        foreach ($minutes as $minute) {
            $time = $minute->format('s');
            $this->assertEquals('00', $time);
        }

        foreach ($minutes2 as $minute) {
            $time = $minute->format('s');
            $this->assertEquals('00', $time);
        }

        foreach ($minutes3 as $minute) {
            $time = $minute->format('s');
            $this->assertEquals('00', $time);
        }
    }

    /**
     * Test overlaps() function.
     */
    public function testOverlap()
    {
        $timerange1 = new TimeRange('2014-01-01 00:00:00', '2014-01-01 23:30:30');
        $timerange2 = new TimeRange('2013-01-01 23:30:31', '2013-01-01 23:59:30');
        $timerange3 = new TimeRange('2013-01-01', '2014-01-01 23:59:30');

        $this->assertEquals(false, $timerange1->overlaps($timerange2, TimeRange::YEAR));
        $this->assertEquals(true, $timerange1->overlaps($timerange3, TimeRange::YEAR));
        $this->assertEquals(true, $timerange1->overlaps($timerange3, TimeRange::MONTH));
        $this->assertEquals(true, $timerange1->overlaps($timerange3, TimeRange::DAY));
        $this->assertEquals(true, $timerange1->overlaps($timerange3, TimeRange::HOUR));
        $this->assertEquals(true, $timerange1->overlaps($timerange3, TimeRange::MINUTE));
        $this->assertEquals(true, $timerange1->overlaps($timerange3));
    }

    /**
     * Test overlaps() function with two timeranges.
     */
    public function testOverlapTwoTimeRanges()
    {
        $timerange1 = new TimeRange('2013-01-01 00:00:00', '2013-01-01 23:30:30');
        $timerange2 = new TimeRange('2013-01-01 23:30:31', '2013-01-01 23:59:30');

        $this->assertEquals(false, $timerange1->overlaps($timerange2));
        $this->assertEquals(false, $timerange1->overlaps($timerange2, TimeRange::SECOND));
        $this->assertEquals(true, $timerange1->overlaps($timerange2, TimeRange::MINUTE));
        $this->assertEquals(true, $timerange1->overlaps($timerange2, TimeRange::HOUR));
        $this->assertEquals(true, $timerange1->overlaps($timerange2, TimeRange::DAY));

    }

    /**
     * Test overlaps() function with one TimeRange.
     */
    public function testOverlapOneTimeRange()
    {
        $timerange = new TimeRange('2013-01-01 00:00:00', '2013-01-01 23:30:30');

        $this->assertEquals(true, $timerange->overlaps('2013-01-01 00:00:00'));
        $this->assertEquals(false, $timerange->overlaps('2013-01-01 23:30:31', TimeRange::SECOND));
        $this->assertEquals(true, $timerange->overlaps('2013-01-01 23:30:59', TimeRange::MINUTE));
        $this->assertEquals(true, $timerange->overlaps('2013-01-01 23:59:59', TimeRange::HOUR));
        $this->assertEquals(true, $timerange->overlaps('2013-01-01', TimeRange::DAY));
    }

    /**
     * Test overlaps() function with DateTime parameter
     */
    public function testOverlapWithDateTimeParameter()
    {
        $timerange = new TimeRange('2013-01-01 00:00:00', '2013-01-01 23:30:30');
        $datetime = new DateTime('2013-01-01 23:30:31');
        $this->assertFalse($timerange->overlaps($datetime));
        $this->assertTrue($timerange->overlaps($datetime, TimeRange::MINUTE));
    }

    /**
     * Test overlaps() function with an invalid parameter.
     * 
     * @expectedException InvalidArgumentException
     */
    public function testOverlapWithInvalidParameter()
    {
        $timerange = new TimeRange('2013-01-01 00:00:00', '2013-01-01 23:30:30');
        $timerange->overlaps('your argument is invalid');
    }

    /**
     * Test start date setter.
     * 
     * @expectedException InvalidArgumentException
     */
    public function testSetStart()
    {
        $timerange = new TimeRange('2013-01-01 00:00:00', '2013-01-01 23:30:30');

        $this->assertTrue($timerange->setStart('2013-01-01 12:00:00'));
        // Should throw an exception
        $timerange->setStart('2013-01-01 23:59:59');
    }

    /**
     * Test end date setter.
     * 
     * @expectedException InvalidArgumentException
     */
    public function testSetEnd()
    {
        $timerange = new TimeRange('2013-01-01 00:00:00', '2013-01-01 23:30:30');

        $this->assertTrue($timerange->setEnd('2013-01-01 23:00:00'));
        // Should throw an exception
        $timerange->setEnd('2012-12-31 23:59:59');
    }

    /**
     * Test that the php iterator works.
     */
    public function testIterator()
    {
        $timerange = new TimeRange('2013-01-01', '2013-01-05');
        $count = 0;

        // This should loop all days
        foreach ($timerange as $key => $date) {
            $this->assertEquals($key, $count, 'Invalid iterator key.');
            $count++;
        }

        $this->assertEquals($count, 5, 'Iterating days failed.');
    }

    /**
     * Test start date getter function getStart().
     */
    public function testGetStart()
    {
        $start = new DateTime('2013-01-01 12:30:59');
        $timerange = new TimeRange($start, '2013-01-05');
        $this->assertEquals($timerange->getStart(), $start);
    }

    /**
     * Test end date getter function getEnd().
     */
    public function testGetEnd()
    {
        $end = new DateTime('2013-01-01 12:30:59');
        $timerange = new TimeRange('2013-01-01 00:00:00', $end);
        $this->assertEquals($timerange->getEnd(), $end);
    }

    /**
     * Test changing timerange using datetime objects.
     */
    public function testSetRangeDateTime()
    {
        $start = new DateTime('2014-01-01 12:30:59');
        $end = new DateTime('2014-01-02 14:30:59');
        $timerange = new TimeRange('2013-09-01 12:30:59', '2013-10-02 14:30:59');
        
        $timerange->setRange($start, $end);

        $this->assertEquals($start, $timerange->getStart());
        $this->assertEquals($end, $timerange->getEnd());
    }

    /**
     * Test changing timerange using strings.
     */
    public function testSetRangeString()
    {
        $start = '2014-01-01 12:30:59';
        $end = '2014-01-02 14:30:59';
        $timerange = new TimeRange('2013-09-01 12:30:59', '2013-10-02 14:30:59');
        
        $timerange->setRange($start, $end);

        $this->assertEquals($start, $timerange->getStart()->format('Y-m-d H:i:s'));
        $this->assertEquals($end, $timerange->getEnd()->format('Y-m-d H:i:s'));
    }

    /**
     * Test setting timerange with invalid values.
     * @expectedException InvalidArgumentException
     */
    public function testSetRangeInvalid()
    {
        $start = '2014-01-01 14:31:00';
        $end = '2014-01-01 14:30:59';
        $timerange = new TimeRange('2013-09-01 12:30:59', '2013-10-02 14:30:59');
        
        $timerange->setRange($start, $end);

        $this->fail('Should throw exception');
    }

    /**
     * Test setting timerange with invalid values.
     * @expectedException Exception
     */
    public function testSetRangeInvalidString()
    {
        $start = 'abc';
        $end = '2014-01-01 14:30:59';
        $timerange = new TimeRange('2013-09-01 12:30:59', '2013-10-02 14:30:59');
        
        $timerange->setRange($start, $end);

        $this->fail('Should throw exception');
    }
}
