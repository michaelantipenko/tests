#!/usr/bin/php
<?php
include (__DIR__ . '/../Util/Arguments.php');
include (__DIR__ . '/../UrlInfo/Request.php');

$arguments = new Arguments($argv);
$url = $arguments->get('url');

$request = new Request($url);
$request->types = $arguments->get('types') ? explode(',', $arguments->get('types')) : null;
$request->run();
