<?php

namespace Reedware\NovaFieldManager;

use Closure;
use InvalidArgumentException;
use Illuminate\Support\Traits\Macroable;

class NovaFieldManager
{
	use Macroable;

	/**
	 * The field types that can be constructed.
	 *
	 * @var array
	 */
	protected $fields;

	/**
	 * Creates a new field manager.
	 *
	 * @param  array  $fields
	 *
	 * @return $this
	 */
	public function __construct($fields)
	{
		$this->fields = $fields;
	}

	/**
	 * Creates and returns the specified field.
	 *
	 * @param  string  $type
	 * @param  array   $parameters
	 *
	 * @return \Laravel\Nova\Fields\Field
	 *
	 * @throws \InvalidArgumentException
	 */
	public function make($type, $parameters = [])
	{
		// If the type exists as a macro, call the macro instead
		if(static::hasMacro($type)) {
			return static::callMacro($type, $parameters);
		}

		// Determine the field class
		$class = $this->fields[$type] ?? $type;

		// Make sure the class exists
		if(!class_exists($class)) {
			throw new InvalidArgumentException("Unable to create field type [{$type}] because class [{$class}] does not exist.");
		}

		// Create and return the new field
		return $class::make(...$parameters);
	}

	/**
	 * Calls the specified macro and returns its result.
	 *
	 * @param  string  $macro
	 * @param  array   $parameters
	 *
	 * @return mixed
	 */
	public static function callMacro($macro, $parameters = [])
	{
		// If the macro is a closure, bind this instance to it before invoking it
        if(static::$macros[$macro] instanceof Closure) {
            return call_user_func_array(Closure::bind(static::$macros[$macro], null, static::class), $parameters);
        }

        // Call the macro as-is
        return call_user_func_array(static::$macros[$macro], $parameters);
	}

	/**
	 * Dynamically creates and returns a new field.
	 *
	 * @param  string  $method
	 * @param  array   $parameters
	 *
	 * @return \Laravel\Nova\Fields\Field
	 */
	public function __call($method, $parameters = [])
	{
		return $this->make($method, $parameters);
	}

	/**
	 * Returns the field types.
	 *
	 * @return array
	 */
	public function getFieldTypes()
	{
		return $this->fields;
	}
}