<?php

require_once __DIR__.'/silex.phar';

use Silex\Application;
use Leach\Container\SilexContainer;

$app = new Application();

$app->get('/hello/{name}', function($name) use($app) {
    return 'Hello '.$app->escape($name);
});

return new SilexContainer($app);
