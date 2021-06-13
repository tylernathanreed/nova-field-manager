<?php

namespace Reedware\NovaFieldManager\Contracts;

interface Guesser
{
    /**
     * Guesses the resource parameter index for the specified class.
     *
     * @param  string  $class
     *
     * @return integer|null
     */
    public function guessIndex(string $class) : ?int;

    /**
     * Guesses the resource class for the specified class and parameters.
     *
     * @param  string  $class
     * @param  array   $parameters
     *
     * @return string|null
     */
    public function guessResource(string $class, array $parameters) : ?string;
}
