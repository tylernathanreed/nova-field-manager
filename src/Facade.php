<?php

namespace Reedware\NovaFieldManager;

use Illuminate\Support\Facades\Facade as BaseFacade;

/**
 * @see \Reedware\NovaFieldManager\NovaFieldManager
 */
class Facade extends BaseFacade
{
    /**
     * Returns the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return NovaFieldManager::class;
    }
}