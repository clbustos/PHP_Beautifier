<?php
error_reporting(E_ALL|E_STRICT);
require_once ('./Beautifier.php');
require_once ('./Beautifier/Batch.php');
    $oBeaut = new PHP_Beautifier();
    $oBatch = new PHP_Beautifier_Batch($oBeaut);
    $oBatch->addFilter('ArrayNested');
    $oBatch->addFilter('NewLines', array(
        'before'=>'if:switch:T_CLASS:function',
        'after'=>'T_COMMENT'
    ));
    $oBatch->setCompress(true);
    $oBatch->setRecursive(true);
    $oBatch->setInputFile('test2/*.phpt');
    $oBatch->setOutputFile('test2_beautified/');
    $oBatch->process();
    $oBatch->save();
?>