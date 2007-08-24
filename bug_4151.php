<?php
require_once 'PHP/Beautifier.php';

$php = new PHP_Beautifier();
$php->addFilter('Pear',array('add_header'=>'pear'));
$php->addFilter('ArrayNested');
$php->addFilter('IndentStyles',array('style'=>'Allman'));


$php->setInputFile('test.php');
$php->setOutputFile('test.php.beautified');

$php->setIndentNumber(4);

$php->process();
echo "<p>Finished</p>\n";
$php->save();
?>