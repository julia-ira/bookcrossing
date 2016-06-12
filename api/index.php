<?php

require 'vendor/autoload.php';
require_once 'vendor/NotORM.php';

$app = new \Slim\App();
//session should be called first
require 'routes/session.php';
require 'routes/users.php';
require 'routes/books.php';

$app->run();