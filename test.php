<?php
error_reporting(E_ALL|E_STRICT);
require_once './Beautifier.php';
    $oBeaut = new PHP_Beautifier();
    //$oBeaut->addFilter("Pear");
    $oBeaut->setInputFile('examples/example_main.php');
    $oBeaut->process();
    $oBeaut->show();
?>                                                