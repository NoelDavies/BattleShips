<?php

namespace NoelDavies\BattleShips;

use NoelDavies\BattleShips\Coordinate;
use NoelDavies\BattleShips\Ship;
use NoelDavies\BattleShips\Exception\PlacementException;
use NoelDavies\BattleShips\Exception\PlacementCollisionException;

class Grid {

    /**
     * Length of one side of the board
     * @var integer
     */
    private $size;

    /**
     * Historical log of shots on this board
     * @var array
     */
    private $history = [];

    /**
     * Ships on the current grid
     * @var Ship[]
     */
    private $ships = [];

    public function __construct($size = 10)
    {
        $this->size = $size;
    }

    /**
     * Places a ship on the current grid
     * @param  Ship   $ship Instance of the ship
     * @param  integer $x    X-Coordinate of the Bow (front) of the ship
     * @param  integer $y    Y-Coordinate of the Bow (front) of the ship
     * @return boolean       True on successful ship placement, False on failure
     */
    public function placeShip(Ship $ship, $x, $y)
    {
        $this->fitShip($x, $y);
        $ship->addPoint(new Coordinate($x, $y));

        $length = $ship->getLength();

        switch ($ship->getOrientation()) {
            case Ship::ORIENTATION_HORIZONTAL:
                for ($i=1; $i < $length; $i++) {
                    $newX = $x+$i;
                    $newY = $y;
                    $this->fitShip($newX, $newY);
                    $ship->addPoint(new Coordinate($newX, $newY));
                }
                break;

            case Ship::ORIENTATION_VERTICAL:
                for ($i=1; $i < $length; $i++) {
                    $newX = $x;
                    $newY = $y+$i;
                    $this->fitShip($newX, $newY);
                    $ship->addPoint(new Coordinate($newX, $newY));
                }
                break;
        }

        $this->ships[] = $ship;

        return true;
    }

    public function getShips()
    {
        return $this->ships;
    }

    public function fitShip($x, $y)
    {
        $this->checkBounds($x, $y);
        $this->checkShipCollision($x, $y);
    }

    private function checkBounds($x, $y)
    {
        if ($x < 0 || $y < 0 || $x > $this->size || $y > $this->size) {
            throw new PlacementException($x, $y);
        }
    }

    private function checkShipCollision($x, $y)
    {
        foreach ($this->ships as $ship) {
            if ($ship->checkShot($x, $y)) {
                throw new PlacementCollisionException($x, $y);
            }
        }
    }

}
