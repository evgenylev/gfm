<?php
require __DIR__.'/classes/Autoloader.php';

Autoloader::register();

$aggregator = \classes\AggregatorBase::getAggregator(\classes\sms\ZazuMedia::class, __DIR__.'/ZazuMedia.conf.php');
echo $aggregator->send(['79539554808'], 'test');