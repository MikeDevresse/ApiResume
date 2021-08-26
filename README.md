[![PHP Tests](https://github.com/MikeDevresse/ApiResume/actions/workflows/php_tests.yml/badge.svg)](https://github.com/MikeDevresse/ApiResume/actions/workflows/php_tests.yml)
[![CodeFactor](https://www.codefactor.io/repository/github/mikedevresse/apiresume/badge)](https://www.codefactor.io/repository/github/mikedevresse/apiresume)
[![codecov](https://codecov.io/gh/MikeDevresse/ApiResume/branch/dev/graph/badge.svg?token=DFAVI70FIG)](https://codecov.io/gh/MikeDevresse/ApiResume)
[![Codacy Badge](https://app.codacy.com/project/badge/Grade/6c4ff99389ff474a81a4b5fbcebbe507)](https://www.codacy.com/gh/MikeDevresse/ApiResume/dashboard?utm_source=github.com&amp;utm_medium=referral&amp;utm_content=MikeDevresse/ApiResume&amp;utm_campaign=Badge_Grade)
# ApiResume

## Introduction
This project contains (at least will !) an api that could be used to build a resume (will add a documentation on how this works but it will have routes to create job experiences, schools, informations about you ...). It is a personnal project that I do to put all my knowledges in. This project is made with [Symfony](https://github.com/symfony/symfony) and [API Platform](https://api-platform.com/) for the back-end part.
I decided to dockerize this application using an custom made php image, an nginx one and a postgres one, plan is tu replace nginx with Swoole not really because I need it performance wise but mainly for learning before using it in a differnet project.
I am also planning to use kubernetes on this project (overkill I know) and publish it so that anyone can use it.

## Installation
### With docker (recomended)
#### Requirements
- Docker and docker-compose

#### Installation
- Download or clone the project
- Go to the root folder
- Run `docker-compose up -d`, this will download and build docker images needed to run the project
- Install composer dependencies `docker-compose exec api_php composer install`
- Initialize database `docker-compose exec api_php php bin/console doctrine:database:create`
- Apply migrations `docker-compose exec api_php php bin/console doctrine:migrations:migrate`
- Go to [http://127.0.0.1](http://127.0.0.1)

### Without docker
#### Requirements
- PHP 8.0
- A database
- [Symfony](https://symfony.com/download)
- [Composer](https://getcomposer.org/)

#### Installation
- Download or clone the project
- Go to the root folder
- Install composer dependencies `composer install`
- copy .env to .env.local and edit it to your needs (database credentials)
- Initialize database `php bin/console doctrine:database:create`
- Apply migrations `php bin/console doctrine:migrations:migrate`
- Run `symfony serve -d` and go to the url that it returned (usualy [http://127.0.0.1:8000](http://127.0.0.1:8000)

## Contributing
Feel free to contribute to this project. Every contributions is accepted, if you see an issue in code or you would like to suggest something, I do this in order to learn all feedbacks are appreciated !
