<?php

namespace NoelDavies\BattleShips;

class GridOutputTest implements GridOutputInterface
{
    private $grid = null;
    private $history = null;

    const TILE_MISS = '-';
    const TILE_HIT = 'x';
    const TILE_UNKNOWN = '.';

    public function output(Grid $grid)
    {
        $this->history = $grid->getHistory();
        $this->grid = $grid;

        $output = '  1234567890'."\n";
        $size = $grid->getSize();

        for ($y = 1; $y <= $size; $y++) {
            $output .= $this->numberToLetter($y).' ';
            for ($x = 1; $x <= $size; $x++) {
                $output .= $this->getPlayableColumn($x, $y);
            }
            if ($size != $y) {
                $output .= "\n";
            }
        }

        return $output;
    }

    public function numberToLetter($i)
    {
        $alpha = range('A', 'Z');

        return $alpha[$i - 1];
    }

    public function getPlayableColumn($x, $y)
    {
        foreach ($this->history as $entry) {
            $pos = $entry['GUESS'];

            if ($pos->getPositionY() === $y && $pos->getPositionX() === $x) {
                if ($entry['RESULT'] === Grid::SHOT_HIT) {
                    return self::TILE_HIT;
                }

                if ($entry['RESULT'] === Grid::SHOT_MISS) {
                    return self::TILE_MISS;
                }
            }
        }

        return self::TILE_UNKNOWN;
    }

    public function reveal(Grid $grid)
    {
        $output = '';
        $points = $this->getPointsOfShips($grid);
        $size = $grid->getSize();

        for ($y = 1; $y <= $size; $y++) {
            for ($x = 1; $x <= $size; $x++) {
                $tile = self::TILE_UNKNOWN;

                foreach ($points as $point) {
                    if ($point->checkShot($x, $y)) {
                        $tile = self::TILE_HIT;
                    }
                }

                $output .= $tile;
                $tile = null;
            }
            if ($size != $y) {
                $output .= "\n";
            }
        }

        return $output;
    }

    public function getPointsOfShips($grid)
    {
        $points = [];

        foreach ($grid->getShips() as $ship) {
            $points = array_merge($points, $ship->getPoints());
        }

        return $points;
    }
}
