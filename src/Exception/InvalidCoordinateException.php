<?php

namespace NoelDavies\BattleShips\Exception;

class InvalidCoordinateException extends \InvalidArgumentException
{
    public function __construct($x, $y)
    {
        parent::__construct(sprintf('%s, %s is an invalid coordinate', var_export($x, true), var_export($y, true)));
    }
}
