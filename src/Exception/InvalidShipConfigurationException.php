<?php

namespace NoelDavies\BattleShips\Exception;

class InvalidShipConfigurationException extends \InvalidArgumentException
{
    public function __construct($x, $y)
    {
        parent::__construct(sprintf(
            '%s, %s is not a valid ship configuration',
            var_export($x, true),
            var_export($y, true)
        ));
    }
}
