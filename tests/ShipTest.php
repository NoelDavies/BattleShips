<?php

use NoelDavies\BattleShips\Coordinate;
use NoelDavies\BattleShips\Ship;

class ShipTest extends PHPUnit_Framework_TestCase {

    public function testShipCreate()
    {
        $this->assertInstanceOf('NoelDavies\BattleShips\Ship', new Ship(4));
    }

    public function testShipCreateDefault()
    {
        $ship = new Ship(4);
        $length = $ship->getLength();
        $orientation = $ship->getOrientation();

        $this->assertEquals($length, 4);
        $this->assertEquals($orientation, Ship::ORIENTATION_HORIZONTAL);
        $this->assertFalse($ship->isSunk());
        $this->assertFalse($ship->anyHits());
        $this->assertEquals($ship->countHits(), 0);
    }

    public function testShipOrientation()
    {
        $ship = new Ship(4, Ship::ORIENTATION_VERTICAL);
        $orientation = $ship->getOrientation();

        $this->assertEquals($orientation, Ship::ORIENTATION_VERTICAL);
    }

    /**
     * @expectedException NoelDavies\BattleShips\Exception\InvalidShipLengthException
     * @expectedExceptionMessage false is not a valid ship length
     */
    public function testInvalidShipLengthWithBoolean()
    {
        $ship = new Ship(false);
    }

    /**
     * @expectedException NoelDavies\BattleShips\Exception\InvalidShipLengthException
     * @expectedExceptionMessage 'a' is not a valid ship length
     */
    public function testInvalidShipLengthWithLetter()
    {
        $ship = new Ship('a');
    }

    public function testPointsCanBeAddedToAShip()
    {
        $point = new Coordinate(1,2);
        $ship  = new Ship(4);

        $ship->addPoint($point);
        $this->assertEquals($ship->getPoints(), [$point]);
    }

    public function testMultiplePointsCanBeAddedToAShip()
    {
        $point    = new Coordinate(1,1);
        $newPoint = new Coordinate(2,1);
        $ship     = new Ship(4);

        $ship->addPoint($point);
        $ship->addPoint($newPoint);
        $this->assertEquals($ship->getPoints(), [$point, $newPoint]);
    }

    /**
     * @expectedException NoelDavies\BattleShips\Exception\InvalidShipConfigurationException
     * @expectedExceptionMessage 3, 5 is not a valid ship configuration
     */
    public function testShipsAreKeptTrueToOrientation()
    {
        $points = [
            new Coordinate(1,1),
            new Coordinate(2,1),
            new Coordinate(3,5)
        ];

        $ship     = new Ship(4);

        foreach ($points as $point) {
            $ship->addPoint($point);
        }
    }

    public function testAddingTooManyPointsToAShip()
    {
        $ship = new Ship(4);

        $points = [
            new Coordinate(1,1),
            new Coordinate(2,1),
            new Coordinate(3,1),
            new Coordinate(4,1)
        ];

        foreach ($points as $point) {
            $ship->addPoint($point);
        }

        $result = $ship->isNextValidPoint(new Coordinate(5,1));
        $this->assertFalse($result);
    }

    public function testAddingDuplicatePointsToAShip()
    {
        $ship = new Ship(4);

        $ship->addPoint(new Coordinate(1,1));

        $result = $ship->isNextValidPoint(new Coordinate(1,1));
        $this->assertFalse($result);
    }

    /**
     * @expectedException NoelDavies\BattleShips\Exception\InvalidShipConfigurationException
     * @expectedExceptionMessage 3, 1 is not a valid ship configuration
     */
    public function testAddingOutOfSequencePoints()
    {
        $ship = new Ship(4);

        $points = [
            new Coordinate(5,1),
            new Coordinate(3,1),
            new Coordinate(1,1),
            new Coordinate(4,1)
        ];

        foreach ($points as $point) {
            $ship->addPoint($point);
        }
    }

    public function testCountsHits()
    {
        $ship = new Ship(4);

        $points = [
            new Coordinate(5,1),
            new Coordinate(4,1)
        ];

        foreach ($points as $point) {
            $ship->addPoint($point);
        }

        $ship->receiveShot(5,1);

        $this->assertEquals($ship->countHits(), 1);
        $this->assertFalse($ship->isSunk());
        $this->assertTrue($ship->anyHits());
    }

    public function testIsSunk()
    {
        $ship = new Ship(4);

        $points = [
            new Coordinate(5,1),
            new Coordinate(4,1),
            new Coordinate(3,1),
            new Coordinate(2,1)
        ];

        foreach ($points as $point) {
            $ship->addPoint($point);
        }

        $ship->receiveShot(5,1);
        $ship->receiveShot(4,1);
        $ship->receiveShot(3,1);
        $ship->receiveShot(2,1);

        $this->assertEquals($ship->countHits(), 4);
        $this->assertTrue($ship->isSunk());
        $this->assertTrue($ship->anyHits());
    }
}