<?php

use NoelDavies\BattleShips\Grid;
use NoelDavies\BattleShips\Ship;

class GridTest extends PHPUnit_Framework_TestCase {

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
        $result_2 = $grid->placeship($battleship_2, 1,1);

        $this->assertTrue($result_1);
        $this->assertTrue($result_2);
    }
}