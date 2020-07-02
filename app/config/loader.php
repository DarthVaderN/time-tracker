<?php

$loader = new \Phalcon\Loader();

/**
 * We're a registering a set of directories taken from the configuration file
 */
$loader->registerDirs([
                    $config->application->controllersDir,
//                    $config->application->libraryDir,
                    $config->application->modelsDir,

]);
$loader->registerNamespaces([
    'Timer'                     => $config->application->libraryDir,
    'Timer\Forms'               => $config->application->formsDir
]);
function generateDates($start, $end)
{
    $result = [];

    while ($start <= $end) {
        $result[$start->format('Y')][$start->format('m')][] = $start->format('d');
        $start->add(new DateInterval('P1D'));
    }

    return $result;
}
$loader->register();




