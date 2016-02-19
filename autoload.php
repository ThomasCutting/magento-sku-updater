<?php
function autoload_api_classes_80202082016($class)
{
    $classes = [
        'BatchCSVProcessor' => __DIR__ . '/csv_processor.php'
    ];
    //
    if (!empty($classes[$class])) {
        include $classes[$class];
    }
}
spl_autoload_register('autoload_api_classes_80202082016');
// Do nothing.
{
}