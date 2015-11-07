<?php

namespace NoelDavies\BattleShips\Exception;

class OutputWriterException extends \InvalidArgumentException
{
    public function __construct($writer)
    {
        parent::__construct(sprintf('%s is not a valid output writer', var_export($writer, true)));
    }
}
