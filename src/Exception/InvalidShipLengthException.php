<?php

namespace NoelDavies\BattleShips\Exception;

class InvalidShipLengthException extends \InvalidArgumentException
{
    public function __construct($length)
    {
        parent::__construct(sprintf('%s is not a valid ship length', var_export($length, true)));
    }
}
