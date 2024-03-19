# Laravel Toolbox

A set of useful tools for Laravel.

## Why

Laravel is a great framework and Eloquent is a piece of art in terms of ActiveRecord ORM *but* it lacks some useful quirks and features that I decided to integrated trough this library.

## How to use this package

Simple enough, just grab it from composer `composer require ludo237/laravel-toolbox` and it's done. Now you can use it inside your project.

## What is included

### Traits

With time things can change current traits are:

- `Bannable` inject logic into models to interact with a `banned_at` column.
- `CanBeActivate` add logic to a model in order to activate/deactivate it using a timestamp column
- `ExposeTableProperties` it allows the model to expose publicly the table name, the primary key name and his type.
- `HasSlug` automatically creates the logic behind a `slug` column for your model.
- `OwnedByUser` Automatically set the current model as owned by the User model

### Rules

- `MatchPassword` similar to confirmed by Laravel but for already created users
- `RequestNotExpired` validate current request to a date to determine if it's valid or not
- `SecureCryptToken` validate a cryptographically secure token against a plain text
- `ValidColor` validate if the current string is a valid color
- `ValidPrice` determine if the current number is a valid price
- `WithoutSpace` check if a string has space in it

### Query Builders

- `IdentifyBy` get current model by id, uuid or uid

## How to Contribute

Please see [the contribute file](CONTRIBUTING.md) for more information
