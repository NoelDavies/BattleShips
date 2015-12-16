<?php

use NoelDavies\BattleShips\Grid;
use NoelDavies\BattleShips\Ship;

class GridTest extends PHPUnit_Framework_TestCase
{
    public function testGridCreate()
    {
        $this->assertInstanceOf('NoelDavies\BattleShips\Grid', new Grid(10));
    }

    public function testOutOfBoundsPlacement()
    {
        $grid = new Grid(10);
        $ship = new Ship(4);

        $x = 2;
        $y = 2;

        $result = $grid->placeShip($ship, $x, $y);
        $this->assertTrue($result);
        $this->assertEquals($grid->getShips(), [$ship]);
    }

    /**
     * @expectedException Noeldavies\BattleShips\Exception\PlacementException
     * @expectedExceptionMessage -1, 3 is an invalid ship placement
     */
    public function testShipOutOfBoundsLeftPlacement()
    {
        $grid = new Grid(10);
        $ship = new Ship(3);

        $x = -1;
        $y = 3;

        $result = $grid->placeShip($ship, $x, $y);
    }

    /**
     * @expectedException Noeldavies\BattleShips\Exception\PlacementException
     * @expectedExceptionMessage 9, 3 is an invalid ship placement
     */
    public function testShipOutOfBoundsRightPlacement()
    {
        $grid = new Grid(8);
        $ship = new Ship(3);

        $x = 9;
        $y = 3;

        $result = $grid->placeShip($ship, $x, $y);
    }

    /**
     * @expectedException Noeldavies\BattleShips\Exception\PlacementException
     * @expectedExceptionMessage 1, -1 is an invalid ship placement
     */
    public function testShipOutOfBoundsTopPlacement()
    {
        $grid = new Grid(8);
        $ship = new Ship(3);

        $x = 1;
        $y = -1;

        $result = $grid->placeShip($ship, $x, $y);
    }

    /**
     * @expectedException Noeldavies\BattleShips\Exception\PlacementException
     * @expectedExceptionMessage 1, 9 is an invalid ship placement
     */
    public function testShipOutOfBoundsBottomPlacement()
    {
        $grid = new Grid(8);
        $ship = new Ship(3);

        $x = 1;
        $y = 9;

        $result = $grid->placeShip($ship, $x, $y);
    }

    /**
     * @expectedException Noeldavies\BattleShips\Exception\PlacementException
     * @expectedExceptionMessage 9, 1 is an invalid ship placement
     */
    public function testShipOverlapGridPlacementX()
    {
        $grid = new Grid(8);
        $ship = new Ship(3);

        $x = 7;
        $y = 1;

        $result = $grid->placeShip($ship, $x, $y);
    }

    /**
     * @expectedException Noeldavies\BattleShips\Exception\PlacementException
     * @expectedExceptionMessage 1, 9 is an invalid ship placement
     */
    public function testShipOverlapGridPlacementY()
    {
        $grid = new Grid(8);
        $ship = new Ship(4, Ship::ORIENTATION_VERTICAL);

        $x = 1;
        $y = 7;

        $result = $grid->placeShip($ship, $x, $y);
    }

    public function testValidShipPlacement()
    {
        $grid = new Grid(10);
        $ship = new Ship(4);

        $x = 1;
        $y = 1;

        $result = $grid->placeship($ship, $x, $y);
        $this->assertTrue($result);
    }

    /**
     * @expectedException NoelDavies\BattleShips\Exception\PlacementCollisionException
     * @expectedExceptionMesaage 4, 3 collides with another ship
     */
    public function testShipCollisionPlacement()
    {
        $grid = new Grid(10);
        $battleship_1 = new Ship(5);
        $battleship_2 = new Ship(5, Ship::ORIENTATION_VERTICAL);

        $grid->placeShip($battleship_1, 3, 3);
        $grid->placeship($battleship_2, 4, 2);
    }

    public function testShipNoCollisionPlacement()
    {
        $grid = new Grid(10);
        $battleship_1 = new Ship(5);
        $battleship_2 = new Ship(5, Ship::ORIENTATION_VERTICAL);

        $result_1 = $grid->placeShip($battleship_1, 3, 3);
        $result_2 = $grid->placeship($battleship_2, 1, 1);

        $this->assertTrue($result_1);
        $this->assertTrue($result_2);
    }

    public function testShotsHitMissSink()
    {
        $grid = new Grid(10);
        $ship1 = new Ship(4, Ship::ORIENTATION_VERTICAL);
        $grid->placeShip($ship1, 1, 1);
        $this->assertEquals(Grid::SHOT_MISS, $grid->receiveShot(5, 5));
        $this->assertEquals(Grid::SHOT_MISS, $grid->receiveShot(8, 4));
        $this->assertEquals(Grid::SHOT_MISS, $grid->receiveShot(1, 5));
        $this->assertEquals(Grid::SHOT_HIT, $grid->receiveShot(1, 4));
        $this->assertEquals(Grid::SHOT_HIT, $grid->receiveShot(1, 3));
        $this->assertEquals(Grid::SHOT_HIT, $grid->receiveShot(1, 1));
        $this->assertEquals(Grid::SHOT_SUNK, $grid->receiveShot(1, 2));
    }

    public function testScoreTracking()
    {
        $grid = new Grid(10);
        $ship1 = new Ship(4, Ship::ORIENTATION_VERTICAL);
        $grid->placeShip($ship1, 1, 1);
        $grid->receiveShot(5, 5);
        $grid->receiveShot(8, 4);
        $grid->receiveShot(1, 5);
        $grid->receiveShot(1, 4);
        $grid->receiveShot(1, 3);
        $grid->receiveShot(1, 1);
        $grid->receiveShot(1, 2);
        $this->assertEquals(7, $grid->getShotsCount());
        $this->assertEquals(4, $grid->getHitsCount());
        $this->assertEquals(1, $grid->getSinksCount());
    }

    public function testBasicOutputBlank()
    {
        $grid = new Grid(10);
        $result = $grid->output();
        $this->assertEquals(file_get_contents(dirname(__FILE__).'/mocks/outputsimple/blank.txt'), $result);
    }

    public function testBasicOutputHits()
    {
        $grid = new Grid(10);

        $grid->receiveShot(1, 1);
        $grid->receiveShot(2, 2);
        $grid->receiveShot(3, 3);
        $grid->receiveShot(4, 4);
        $grid->receiveShot(5, 5);
        $grid->receiveShot(6, 6);
        $grid->receiveShot(7, 7);
        $grid->receiveShot(8, 8);
        $grid->receiveShot(9, 9);
        $grid->receiveShot(10, 10);

        $result = $grid->output();
        $this->assertEquals(file_get_contents(dirname(__FILE__).'/mocks/outputsimple/misses.txt'), $result);
    }

    public function testRevealAdvancedOutput()
    {
        $grid = new Grid(10);

        $ship1 = new Ship(4);
        $ship2 = new Ship(4);
        $ship3 = new Ship(5, Ship::ORIENTATION_VERTICAL);

        $grid->placeShip($ship1, 1, 1);
        $grid->placeShip($ship2, 5, 2);
        $grid->placeShip($ship3, 10, 3);

        $result = $grid->reveal();
        $this->assertEquals(file_get_contents(dirname(__FILE__).'/mocks/outputsimple/reveal_ships.txt'), $result);
    }

    public function testActualOutputTest()
    {
        $grid = new Grid(10);
        $grid->setOutputClass('NoelDavies\BattleShips\GridOutputTest');

        $grid->receiveShot(1, 1);

        $result = $grid->output();
        $this->assertEquals(file_get_contents(dirname(__FILE__).'/mocks/outputsimple/1_1_test.txt'), $result);
    }
}
