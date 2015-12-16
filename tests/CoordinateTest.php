<?php

use NoelDavies\BattleShips\Coordinate;

class CoordinateTest extends PHPUnit_Framework_TestCase
{
    public function testGridCreate()
    {
        $this->assertInstanceOf('NoelDavies\BattleShips\Coordinate', new Coordinate(1, 1));
    }

    public function testInitialValues()
    {
        $point = new Coordinate(1, 2);

        $this->assertFalse($point->isHit());
        $this->assertEquals($point->getPositionX(), 1);
        $this->assertEquals($point->getPositionY(), 2);
    }

    /**
     * @expectedException NoelDavies\BattleShips\Exception\InvalidCoordinateException
     * @expectedExceptionMessage true, false is an invalid coordinate
     */
    public function testInvalidCoordinateValues()
    {
        $point = new Coordinate(true, false);
    }

    public function testSetHit()
    {
        $point = new Coordinate(1, 1);
        $point->setHit();
        $result = $point->isHit();

        $this->assertTrue($result);
    }

    public function testSuccessfulHit()
    {
        $point = new Coordinate(1, 1);

        $x = 1;
        $y = 1;

        $result = $point->checkShot($x, $y);
        $coordinateHit = $point->isHit();

        $this->assertTrue($result);
        $this->assertFalse($coordinateHit);
    }

    public function testFailedHit()
    {
        $point = new Coordinate(1, 1);

        $x = 2;
        $y = 1;

        $result = $point->checkShot($x, $y);

        $this->assertFalse($result);
    }

    public function testSuccessfulReceiveShot()
    {
        $point = new Coordinate(1, 1);

        $x = 1;
        $y = 1;

        $result = $point->receiveShot($x, $y);
        $coordinateHit = $point->isHit();

        $this->assertTrue($result);
        $this->assertTrue($coordinateHit);
    }

    public function testFailedReceiveShot()
    {
        $point = new Coordinate(1, 1);

        $x = 2;
        $y = 1;

        $result = $point->receiveShot($x, $y);
        $coordinateHit = $point->isHit();

        $this->assertFalse($result);
        $this->assertFalse($coordinateHit);
    }
}
