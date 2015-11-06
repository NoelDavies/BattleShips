<?php

namespace NoelDavies\BattleShips\Exception;

class InvalidOrientationException extends \InvalidArgumentException
{
    public function __construct($x, $y)
    {
        parent::__construct(sprintf(
            '%s, %s is not a valid ship orientation',
            var_export($x, true),
            var_export($y, true)
        ));
    }
}
