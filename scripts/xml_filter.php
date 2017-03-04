#!/usr/bin/php
<?php
include __DIR__ . '/../Xml/EntityReader.php';
include __DIR__ . '/../Xml/UserFilter.php';
include __DIR__ . '/../Xml/EntityWriter.php';
include __DIR__ . '/../Util/Arguments.php';

$arguments = new Arguments($argv);
$file = $arguments->get('file');
$output = $arguments->get('output');

$rootTag = $arguments->get('rootTag') ? $arguments->get('rootTag') : 'users';
$itemTag = $arguments->get('itemTag') ? $arguments->get('itemTag') : 'user';

$reader = new EntityReader($file);
$reader->rootTag = $rootTag;
$reader->itemTag = $itemTag;
$reader->tags = ['id', 'name', 'age', 'email'];

$filter = new UserFilter();
$filter->id = $arguments->get('id');
$filter->name = $arguments->get('name');
$filter->age = $arguments->get('age');
$filter->email = $arguments->get('email');

$writer = new EntityWriter($output);
$writer->rootTag = $rootTag;
$writer->itemTag = $itemTag;

$writer->start();
foreach ($reader->read() as $item) {
    if ($filter->filter($item)) {
        $writer->addItem($item);
    }
}
$writer->end();

echo "The End" . PHP_EOL;
