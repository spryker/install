<?php

require __DIR__ . '/../vendor/autoload.php';

use Spryker\Console\SetupConsoleCommand;
use Symfony\Component\Console\Application;

define('SPRYKER_ROOT', getcwd());

$application = new Application();

$application->addCommands([
    new SetupConsoleCommand(),
]);

$application->run();
