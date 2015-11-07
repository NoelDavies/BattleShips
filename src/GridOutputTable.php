<?php

namespace NoelDavies\BattleShips;

class GridOutputTable implements GridOutputInterface
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

        $output = '<table>';
        $size = $grid->getSize();

        for ($y=1; $y <= $size; $y++) {

            $output .= '<tr>';

            for ($x=1; $x <= $size; $x++) {
                $output .= $this->getPlayableColumn($x, $y);
            }

            $output .= '</tr>';
        }

        return $output;
    }

    public function getPlayableColumn($x, $y)
    {
        foreach ($this->history as $entry) {
            $pos = $entry['GUESS'];

            if ($pos->getPositionY() === $y && $pos->getPositionX() === $x) {
                if ( $entry['RESULT'] === Grid::SHOT_HIT) {
                    return $this->asCell(self::TILE_HIT);
                }

                if ( $entry['RESULT'] === Grid::SHOT_MISS) {
                    return $this->asCell(self::TILE_MISS);
                }
            }
        }
        return $this->asCell(self::TILE_UNKNOWN);
    }

    public function reveal(Grid $grid)
    {
        $output = '<table>';
        $points = $this->getPointsOfShips($grid);
        $size   = $grid->getSize();

        for ($y=1; $y <= $size; $y++) {
            $output .= '<tr>';
            for ($x=1; $x <= $size; $x++) {
                $tile = self::TILE_UNKNOWN;

                foreach ($points as $point) {
                    if ($point->checkShot($x, $y)) {
                        $tile = self::TILE_HIT;
                    }
                }
                $output .= $this->asCell($tile);
                $tile = null;
            }

            $output .= '</tr>';
        }

        return $output . '</table>';
    }

    public function getPointsOfShips($grid)
    {
        $points = [];

        foreach ($grid->getShips() as $ship) {
            $points = array_merge($points, $ship->getPoints());
        }

        return $points;
    }

    public function asCell($value)
    {
        return '<td style="text-align: center; font-weight: bold;">' . $value . '</td>';
    }
}