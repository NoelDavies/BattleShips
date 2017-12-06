<?php

namespace NoelDavies\BattleShips;

use NoelDavies\BattleShips\Exception\PlacementCollisionException;
use NoelDavies\BattleShips\Exception\PlacementException;

class Grid
{
    const SHOT_MISS = 1;
    const SHOT_HIT = 2;
    const SHOT_SUNK = 3;

    /**
     * Length of one side of the board.
     *
     * @var int
     */
    private $size;

    /**
     * Historical log of shots on this board.
     *
     * @var array
     */
    private $history = [];

    /**
     * Ships on the current grid.
     *
     * @var Ship[]
     */
    private $ships = [];

    /**
     * For when those insults go flying!
     *
     * @var int
     */
    private $shotsFired = 0;

    /**
     * Number of shots that have hit.
     *
     * @var int
     */
    private $shotsHit = 0;

    /**
     * Number of shots that have caused a ship to sink.
     *
     * @var int
     */
    private $shotsSunk = 0;

    private $outputWriter = '';

    public function __construct($size = 10)
    {
        $this->size = $size;
        $this->setOutputClass('NoelDavies\BattleShips\GridOutputSimple');
    }

    public function setOutputClass($writer)
    {
        if (class_exists($writer) === false) {
            throw new OutputWriterException($writer);
        }

        if (in_array('NoelDavies\BattleShips\GridOutputInterface', class_implements($writer)) === false) {
            throw new OutputWriterException($writer);
        }

        $this->outputWriter = $writer;
    }

    /**
     * Places a ship on the current grid.
     *
     * @param Ship $ship Instance of the ship
     * @param int  $x    X-Coordinate of the Bow (front) of the ship
     * @param int  $y    Y-Coordinate of the Bow (front) of the ship
     *
     * @return bool True on successful ship placement, False on failure
     */
    public function placeShip(Ship $ship, $x, $y)
    {
        $this->fitShip($x, $y);
        $ship->addPoint(new Coordinate($x, $y));

        $length = $ship->getLength();

        switch ($ship->getOrientation()) {
            case Ship::ORIENTATION_HORIZONTAL:
                for ($i = 1; $i < $length; $i++) {
                    $newX = $x + $i;
                    $newY = $y;
                    $this->fitShip($newX, $newY);
                    $ship->addPoint(new Coordinate($newX, $newY));
                }
                break;

            case Ship::ORIENTATION_VERTICAL:
                for ($i = 1; $i < $length; $i++) {
                    $newX = $x;
                    $newY = $y + $i;
                    $this->fitShip($newX, $newY);
                    $ship->addPoint(new Coordinate($newX, $newY));
                }
                break;
        }

        $this->ships[] = $ship;

        return true;
    }

    /**
     * Retrieve all the ships on the grid.
     *
     * @return Ship[] Array of ships on the grid
     */
    public function getShips()
    {
        return $this->ships;
    }

    /**
     * Check if the ship fits on the board without collisions.
     *
     * @param int $x X coord of the bow of the ship
     * @param int $y Y coord of the bow of the ship
     *
     * @return void
     */
    public function fitShip($x, $y)
    {
        $this->checkBounds($x, $y);
        $this->checkShipCollision($x, $y);
    }

    /**
     * Check the coord is within the bounds of the grid.
     *
     * @param int $x X coordinate
     * @param int $y Y coordinate
     *
     * @return void
     */
    private function checkBounds($x, $y)
    {
        if ($x < 1 || $y < 1 || $x > $this->size || $y > $this->size) {
            throw new PlacementException($x, $y);
        }
    }

    /**
     * Check that a new ship won't collide with one already placed down.
     *
     * @param int $x X coordinate
     * @param int $y Y coordinate
     *
     * @return void
     */
    private function checkShipCollision($x, $y)
    {
        foreach ($this->ships as $ship) {
            if ($ship->checkShot($x, $y)) {
                throw new PlacementCollisionException($x, $y);
            }
        }
    }

    /**
     * Return different status code depending on outcome of shot.
     *
     * @return int
     */
    public function receiveShot($x, $y)
    {
        $this->shotsFired++;
        $result = self::SHOT_MISS;

        foreach ($this->ships as $ship) {
            if ($ship->receiveShot($x, $y)) {
                $this->shotsHit++;
                $result = self::SHOT_HIT;

                if ($ship->isSunk()) {
                    $this->shotsSunk++;
                    $result = self::SHOT_SUNK;
                }
            }
        }

        $this->history[] = [
            'GUESS'  => new Coordinate($x, $y),
            'RESULT' => $result,
        ];

        return $result;
    }

    public function getSize()
    {
        return $this->size;
    }

    /**
     * Recieve the number of shots fired on this grid.
     *
     * @return int
     */
    public function getShotsCount()
    {
        return $this->shotsFired;
    }

    /**
     * Get the history of the shots.
     *
     * @return int
     */
    public function getHistory()
    {
        return $this->history;
    }

    /**
     * Retrieve the number of shots that have resulted in a hit on this grid.
     *
     * @return int
     */
    public function getHitsCount()
    {
        return $this->shotsHit;
    }

    /**
     * Retrieve the number of shots that have resulted in sinkages.
     *
     * @return int
     */
    public function getSinksCount()
    {
        return $this->shotsSunk;
    }

    public function output()
    {
        $writer = new $this->outputWriter();

        return $writer->output($this);
    }

    public function reveal()
    {
        $writer = new $this->outputWriter();

        return $writer->reveal($this);
    }
}
