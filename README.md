## Nova Field Manager

This package provides the convenience of not having to include the class path of each individual field in your resource class declaration. A new `Field` facade will be created, where you can defer all field creation through.

[![Latest Stable Version](https://poser.pugx.org/reedware/nova-field-manager/v/stable)](https://packagist.org/packages/reedware/nova-field-manager)
[![Total Downloads](https://poser.pugx.org/reedware/nova-field-manager/downloads)](https://packagist.org/packages/reedware/nova-field-manager)
[![License](https://poser.pugx.org/reedware/nova-field-manager/license)](https://packagist.org/packages/reedware/nova-field-manager)

## Installation

Require this package with composer.

```shell
composer require reedware/nova-field-manager
```

Laravel 5.5+ uses Package Auto-Discovery, so doesn't require you to manually add the service provider or facade. However, should you still need to reference them, here are their class paths:

```php
\Reedware\NovaFieldManager\NovaFieldManagerServiceProvider::class // Service Provider
\Reedware\NovaFieldManager\Facade::class // Facade
```

## Usage

You can now add fields to your resources in Nova by using the `Field` facade, rather than having to include each individual field in your resource class definition.

```php
/**
 * Get the fields displayed by the resource.
 *
 * @param  \Illuminate\Http\Request  $request
 * @return array
 */
public function fields(Request $request)
{
    return [
        Field::id()->sortable(),

        Field::text('Name', 'display_name')
            ->sortable()
            ->rules('required', 'max:100'),

        Field::textarea('Description')
            ->rules('max:255'),

        Field::hasMany('Tasks', 'tasks', Task::class),

        Field::number('Tasks', function() {
            return $this->tasks_count;
        })

    ];
}
```

## Custom Fields

All of the fields are configured in the `nova-fields` configuration file. First, you will need to create your own copy, by either running the `php artisan vendor:publish` command, or by copying the configuration file directory from this repository (found in `~/config/nova-fields.php`).

You can easily add your own (or override the default fields) by manipulating this configuration file. The config key (`id`, `text`, `hasMany`, etc) is the method that you can call from the `Field` facade. The config value is the class path to the field, where a call to `Field::<key>(...)` defers to `<value>::make(...)`.

The default fields provided by Nova will automatically be added, even if they are not present in your configuration file. However, if you have overridden one of the default fields (by specifying the same key name), your field will override the default Nova field.

```php
<?php

return [
  
  /**
   * Custom Fields
   */
   'custom' => \NovaComponents\CustomField\Field::class

];
```

```php
/**
 * Get the fields displayed by the resource.
 *
 * @param  \Illuminate\Http\Request  $request
 * @return array
 */
public function fields(Request $request)
{
    return [
        Field::custom(...)
    ];
}
