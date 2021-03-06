<?php

use Symfony\Component\Dotenv\Dotenv;

require dirname(__DIR__).'/vendor/autoload.php';

if (file_exists(dirname(__DIR__).'/config/bootstrap.php')) {
    require dirname(__DIR__).'/config/bootstrap.php';
} elseif (method_exists(Dotenv::class, 'bootEnv')) {
    (new Dotenv())->bootEnv(dirname(__DIR__).'/.env');
}
exec('php bin/console doctrine:database:create --env=test --if-not-exists');
exec('php bin/console doctrine:schema:update --force --env=test --complete');
exec('php bin/console doctrine:fixtures:load --env=test --no-interaction');
