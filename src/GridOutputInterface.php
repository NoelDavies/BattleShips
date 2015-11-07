<?php
namespace NoelDavies\BattleShips;

/**
* Output Interface
*/
interface GridOutputInterface
{
    public function output( Grid $grid );
    public function reveal( Grid $grid );
}