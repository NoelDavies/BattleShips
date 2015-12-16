<?php

namespace NoelDavies\BattleShips;

use NoelDavies\BattleShips\Exception\InvalidShipConfigurationException;
use NoelDavies\BattleShips\Exception\InvalidShipLengthException;

class Ship
{
    const ORIENTATION_HORIZONTAL = 1;
    const ORIENTATION_VERTICAL = 2;

    /**
     * Length of the current ship.
     *
     * @var int
     */
    private $length;

    /**
     * Orientation of the current ship.
     *
     * @var int
     */
    private $orientation;

    /**
     * Coordinates of the current ship.
     *
     * @var Coordinate[]
     */
    private $points = [];

    public function __construct($length, $orientation = self::ORIENTATION_HORIZONTAL)
    {
        if (is_int($length) === false) {
            throw new InvalidShipLengthException($length);
        }

        if ($orientation !== self::ORIENTATION_HORIZONTAL && $orientation !== self::ORIENTATION_VERTICAL) {
            throw new InvalidShipOrientationException($orientation);
        }

        $this->length = $length;
        $this->orientation = $orientation;
    }

    /**
     * Adds a point of the ship.
     *
     * @param Coordinate $point Coordinate of the ship
     */
    public function addPoint(Coordinate $point)
    {
        if ($this->isNextValidPoint($point) === false) {
            throw new InvalidShipConfigurationException(
                $point->getPositionX(),
                $point->getPositionY()
            );
        }

        $this->points[] = $point;

        return true;
    }

    /**
     * Returns the length of the ship.
     *
     * @return int
     */
    public function getLength()
    {
        return $this->length;
    }

    /**
     * Returns the current orientation of the ship.
     *
     * @return int 1 = Horizontal, 2 = Vetical
     */
    public function getOrientation()
    {
        return $this->orientation;
    }

    public function getPoints()
    {
        return $this->points;
    }

    public function checkShot($x, $y)
    {
        foreach ($this->points as $point) {
            if ($point->checkShot($x, $y) === true) {
                return true;
            }
        }

        return false;
    }

    public function receiveShot($x, $y)
    {
        foreach ($this->points as $point) {
            if ($point->isHit() === false && $point->receiveShot($x, $y) === true) {
                return true;
            }
        }

        return false;
    }

    /**
     * Determine if a point is a valid next point.
     *
     * @param Coordinate $point Next point to be set
     *
     * @return bool
     */
    public function isNextValidPoint(Coordinate $point)
    {
        // If it's our first point, it's good!
        if ($this->points === []) {
            return true;
        }

        // The ship's size has been met
        if ($this->length <= count($this->points)) {
            return false;
        }

        // If the points exists in our array, it's invalid
        if (in_array($point, $this->points)) {
            return false;
        }

        if ($this->coordinateHasValidOrientation($point) === false) {
            return false;
        }

        if ($this->orientatedCoordinateFollowsSequence($point) === false) {
            return false;
        }

        return true;
    }

    /**
     * Dependant on the orientation of a ship, it's X or Y values
     *     should remain the same.
     *
     * Check the provided point to see if it's valid or not.
     *
     * @param Coordinate $point Point to check
     *
     * @return bool True when the new point has a valid orientation, False on failure.
     */
    public function coordinateHasValidOrientation(Coordinate $point)
    {
        $lastPoint = end($this->points);

        if ($this->orientation === self::ORIENTATION_HORIZONTAL) {
            $lastValue = $lastPoint->getPositionY();
            $newValue = $point->getPositionY();
        } else {
            $lastValue = $lastPoint->getPositionX();
            $newValue = $point->getPositionX();
        }

        if ($lastValue === $newValue) {
            return true;
        }

        return false;
    }

    /**
     * When adding a new point to a ship, each point should have one
     *     axis of which the values are in sequence.
     *
     * Check to see if the new point follows this sequence.
     *
     * @param Coordinate $point Point to check
     *
     * @return bool True when the point follows sequence, False on failure.
     */
    public function orientatedCoordinateFollowsSequence(Coordinate $point)
    {
        $lastPoint = end($this->points);

        if ($this->orientation === self::ORIENTATION_HORIZONTAL) {
            $lastValue = $lastPoint->getPositionX();
            $newValue = $point->getPositionX();
        } else {
            $lastValue = $lastPoint->getPositionY();
            $newValue = $point->getPositionY();
        }

        if ($newValue === ($lastValue - 1) || $newValue === ($lastValue + 1)) {
            return true;
        }

        return false;
    }

    public function isSunk()
    {
        return $this->length == $this->countHits();
    }

    public function anyHits()
    {
        return $this->countHits() > 0;
    }

    public function countHits()
    {
        $hits = 0;
        foreach ($this->points as $point) {
            if ($point->isHit()) {
                $hits++;
            }
        }

        return $hits;
    }
}
