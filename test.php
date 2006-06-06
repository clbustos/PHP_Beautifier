<?php
error_reporting(E_ALL|E_STRICT);
require_once ('./Beautifier.php');
    $oBeaut = new PHP_Beautifier();
    $oBeaut->startLog();
    $oBeaut->addFilter('Lowercase');
    $oBeaut->setInputFile('examples/example_lowercase.php');
    $oBeaut->process();
    $oBeaut->show();
?>