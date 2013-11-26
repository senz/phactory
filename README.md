# Phactory: PHP Database Object Factory for Unit Testing
[![Build Status](https://travis-ci.org/senz/phactory.png)](https://travis-ci.org/senz/phactory)

## What is it?
Phactory is a factory for fixtures, written in PHP and targeted for reusable easy-to-read testing code.

Generally, in tests you should avoid using fixtures and db layer directly.
But what to do when you need to test models (DAO, ActiveRecords, etc...) or quickly test some legacy,
tightly coupled piece of...code?
Here Phactory comes to rescue. Combined with [ObjectMother pattern](http://martinfowler.com/bliki/ObjectMother.html),
it eases the pain from fixture setup,
so you can focus on problem you are testing and not on implementation (almost). Dont forget that there is still some preparation work
before you start: setup of blueprints and establishing common patterns and associations (with help of OM).
Forget about hacky, redundant fixtures.

Phactory was inspired by [Factory girl](https://github.com/thoughtbot/factory_girl).

## Features
* Blueprints - templates for entities with default values that can be easily overriden.
* Associations - link between related entities (many-to-many or one-to-many). Limited to simple integer primary keys.
* Sequences - helps create unique values for each successive entity.
* Entity name inflection - i.e. entity with name "user" goes to "users" table.

## Database Support
* MySQL
* Sqlite
* Postgresql
* MongoDB

## Language Support
* PHP >= 5.3
