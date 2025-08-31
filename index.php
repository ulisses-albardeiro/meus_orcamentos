<?php

require 'vendor/autoload.php';

$container = require 'container.php';

use Pecee\SimpleRouter\SimpleRouter;
use sistema\Nucleo\DIClassLoader;

SimpleRouter::setCustomClassLoader(new DIClassLoader($container));

require 'sistema/rotas.php';
