<?php

namespace Reedware\NovaFieldManager;

use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Laravel\Nova\Fields\MorphTo;
use Reedware\NovaFieldManager\Contracts\Guesser;

class ResourceParameterGuesser implements Guesser
{
    /**
     * The methods capable of defining fields.
     *
     * @var array
     */
    public static $fieldMethods = [
        'fieldsForIndex',
        'fieldsForDetail',
        'fieldsForCreate',
        'fieldsForUpdate',
        'fields',
    ];

    /**
     * Guesses the resource parameter index for the specified class.
     *
     * @param  string  $class
     *
     * @return integer|null
     */
    public function guessIndex(string $class) : ?int
    {
        return is_subclass_of($class, MorphTo::class)
            ? null
            : 2;
    }

    /**
     * Guesses the resource class for the specified class and parameters.
     *
     * @param  string  $class
     * @param  array   $parameters
     *
     * @return string|null
     */
    public function guessResource(string $class, array $parameters) : ?string
    {
        // Determine the field name
        $name = $parameters[0];

        // Guess the class basename
        $singular = Str::studly(Str::singular($name));

        // Perform a backtrace
        $results = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 6);

        // Search for the field method
        $method = Arr::last($results, function($trace) {
            return in_array($trace['function'], static::$fieldMethods) && isset($trace['class']);
        });

        // Bail if the method couldn't be found
        if(is_null($method)) {
            return null;
        }

        // Guess the resource class
        return str_replace(
            class_basename($method['class']),
            $singular,
            $method['class']
        );
    }
}
