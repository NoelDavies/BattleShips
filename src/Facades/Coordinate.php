<?php

namespace NoelDavies\Battleships\Facades;

use Illuminate\Support\Facades\Facade;

class Coordinate extends Facade
{
    /**
   * Get the registered name of the component.
   *
   * @return string
   */
  protected static function getFacadeAccessor()
  {
      return 'noeldavies-battleships-coordinate';
  }
}
