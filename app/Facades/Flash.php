<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class Flash extends Facade {
	/**
	 * Getting the Flash class
	 *
	 * @return string flash
	 */
	protected static function getFacadeAccessor()
    {
        return 'flash';
    }
}