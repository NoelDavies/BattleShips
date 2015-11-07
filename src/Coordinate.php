<?php

namespace NoelDavies\BattleShips;

use NoelDavies\BattleShips\Exception\InvalidCoordinateException;

/**
* Define a position on the grid
*/
class Coordinate
{

    /**
     * X position on the board
     * @var int
     */
    private $x;

    /**
     * Y position on the board
     * @var int
     */
    private $y;

    /**
     * Boolean value repesenting if this point is a hit
     * @var boolean
     */
    private $isHit = false;

    public function __construct($x, $y)
    {
        if (!is_int( $x ) || !is_int( $y )) {
            throw new InvalidCoordinateException($x, $y);
        }

        $this->x = $x;
        $this->y = $y;
    }

    public function getPositionX()
    {
        return $this->x;
    }

    public function getPositionY()
    {
        return $this->y;
    }

    /**
     * Check if this Coordinate is a hit or not
     * @return boolean
     */
    public function isHit()
    {
        return $this->isHit;
    }

    /**
     * Set the current point to a hit
     * @return boolean
     */
    public function setHit()
    {
        return $this->isHit = true;
    }

    /**
     * Check if the coordinates are a hit without affecting this coordinate
     * @param  integer $x X coordinate to check for a hit
     * @param  integer $y Y coordinate to check for a hit
     * @return boolean    true on hit, false on miss
     */
    public function checkShot($x, $y)
    {
        if ($this->x === $x && $this->y === $y) {
            return true;
        }

        return false;
    }

    /**
     * Check a shot and affect the status of this coordinate
     * @param  integer $x X coordinate to check for a hit
     * @param  integer $y Y coordinate to check for a hit
     * @return boolean    True on hit, false on miss
     */
    public function receiveShot($x, $y)
    {
        if ($this->checkShot($x, $y) === true) {
            $this->setHit();
        }

        return $this->isHit();
    }
}
