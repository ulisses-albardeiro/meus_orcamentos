<?php

require 'vendor/autoload.php';

$container = require 'container.php';

use Pecee\SimpleRouter\SimpleRouter;
use App\Core\DIClassLoader;

SimpleRouter::setCustomClassLoader(new DIClassLoader($container));

require 'app/routes.php';
