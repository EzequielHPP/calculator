# Calculator - Using Symfony [![GitHub release](https://img.shields.io/github/release/symfony/symfony?include_prereleases=&sort=semver&color=blue)](https://github.com/symfony/symfony/releases/)

[![License](https://img.shields.io/badge/License-MIT-blue)](#license)

Simple calculator, that uses an API call to get the result of the requested equation.

## Setup

After getting the repo down to your machine, run the following commands on the root of the project

```shell
composer install
npm install
```

After the installation has completed, run the follow command to build the CSS and JS

```shell
npm run build
```

After everything is done and compiled, you can serve it on your local machine running:

```shell
symfony server:start
```

And you will be able to access the system using the following url [http://127.0.0.1:8000](http://127.0.0.1:8000
)

## Available Routes

Browser / Interface routes

| Route             | Method | Description                                                            |
|-------------------|--------|------------------------------------------------------------------------|
| `/`               | `GET`  | Homepage of the project, displays links to the Repo and the Calculator |
| `/calculator`     | `GET`  | The actual calculator                                                  |

API routes

| Route             | Method | Parameters               | Description                                    |
|-------------------|--------|--------------------------|------------------------------------------------
| `/v1/calculator`  | `POST` | ['calculation'=> String] | API Endpoint where the calculations take place |

## Files

### src/Controller/v1/CalculatorController

The main controller for the Calculator. It has two functions:

| Function         | Parameters                                        | Description                                  |
|------------------|---------------------------------------------------|----------------------------------------------|
| `getIndex`       | -none-                                            | Render the Index File to show the calculator |
| `postCalculator` | Symfony\Component\HttpFoundation\Request $request | API Endpoint to calculate the expression     |



### src/Service/CalculatorService

Where the business logic lives for the Calculation of the expression.

This consists on only one `public` function, which is dependent on the
constructor to recieve a valid `Symfony\Component\HttpFoundation\Request`
request with the data.

| Function     | Parameters | Description                                                               |
|--------------|------------|---------------------------------------------------------------------------|
| `calculate`  | -none-     | Given a request, get the calculation property and execute the calculation |

## Tests

The tests are stored under the `/tests` folder in their specific Folders.

![PHPUnit Tests](https://github.com/EzequielHPP/calculator/blob/master/public/img/phpunit_tests.png?raw=true)

To run them, on the root of the project run:
```shell
php bin/phpunit
```
