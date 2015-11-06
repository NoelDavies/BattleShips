<?php

namespace NoelDavies\BattleShips\Exception;

class PlacementCollisionException extends \InvalidArgumentException
{
    public function __construct($x, $y)
    {

        parent::__construct(sprintf('%s, %s  collides with another ship', var_export($x, true), var_export($y, true)));
    }
}
