# Laravel Toolbox

A set of useful tools for Laravel.

## Why

Laravel is a great framework and Eloquent is a piece of art in terms of ActiveRecord ORM *but* it lacks some useful quirks and features that I decided to integrated trough this library.

## How to use this package

Simple enough, just grab it from composer `composer require ludo237/laravel-toolbox` and it's done. Now you can use it inside your project.

## What is included

With time things can change current traits are:

- `Bannable` inject logic into models to interact with a `banned_at` column.
- `CanBeActivate` add logic to a model in order to activate/deactivate it using a timestamp column
- `ExposeTableProperties` it allows the model to expose publicly the table name, the primary key name and his type.
- `HasSlug` automatically creates the logic behind a `slug` column for your model.
- `InteractsWithApi` automatically set the api_key for the current model following Laravel standards.
- `OwnedByUser` Automatically set the current model as owned by the User model
- `Benchmarkable` Start/Stop a timer to benchmark your Artisan Commands

## How to Contribute

Please see [the contribute file](CONTRIBUTING.md) for more information
