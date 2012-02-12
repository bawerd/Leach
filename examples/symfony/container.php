<?php

require_once __DIR__.'/symfony-standard/app/bootstrap.php.cache';
require_once __DIR__.'/symfony-standard/app/AppKernel.php';
// require_once __DIR__.'/symfony-standard/app/AppCache.php';

use Leach\Container\SymfonyContainer;

$kernel = new AppKernel('prod', false);
// $kernel = new AppCache($kernel);
$kernel->loadClassCache();

return new SymfonyContainer($kernel);

