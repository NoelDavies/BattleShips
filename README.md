[![Latest Stable Version](https://img.shields.io/travis/noeldavies/battleships.svg)](https://packagist.org/packages/noeldavies/battleships)
[![Total Downloads](https://img.shields.io/packagist/dt/noeldavies/battleships.svg)](https://packagist.org/packages/noeldavies/battleships)
[![License](https://poser.pugx.org/noeldavies/battleships/license)](https://packagist.org/packages/noeldavies/battleships)
# BattleShips

This package provides a convenient library for playing BattleShips. Be it simulations, live games or you're trying to create a scene from the film wargames!

Begin by installing this package through Composer. Edit your project's `composer.json` file to require `noeldavies/battleships`.

    "require": {
        "noeldavies/battleships": "dev-master"
    }

Next, update Composer from the Terminal:

    composer update

Next, add the service provider. Open `config/app.php` and add a new item to the providers array.

    NoelDavies\BattleShips\BattleShipsServiceProvider::class


## Usage

This package is accessible via Laravel Facades so to use simply use one of the Facades "Coordinate", "Grid" and "Ship".

## Contributing
Just fork it and send me a PR. Please don't break my tests :/