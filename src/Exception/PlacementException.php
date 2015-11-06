<?php

namespace NoelDavies\BattleShips\Exception;

class PlacementException extends \InvalidArgumentException
{
    public function __construct($x, $y)
    {

        parent::__construct(sprintf('%s, %s is an invalid ship placement', var_export($x, true), var_export($y, true)));
    }
}
