<?php
    error_reporting(E_ALL|E_STRICT);
    require_once ('./Beautifier.php');
    require_once ('./Beautifier/Batch.php');
    try {
        $oBeaut = new PHP_Beautifier();
        $oBatch = new PHP_Beautifier_Batch($oBeaut);
        //$oBatch->setFileType('js');
        //$oBatch->addFilter('Pear');
        $oBatch->addFilter('NewLines',array('after'=>'for,class'));
        //$oBatch->addFilter('ListClassFunction');
        //$oBatch->setRecursive(true);
        //unlink('test2.php');
        $oBatch->setInputFile('./examples/example_main.php');
        $oBatch->setOutputFile('test2.php');
        //$oBatch->setCompress('gz');
        $oBatch->process();
        //$oBatch->save();
        // php_beautifier->setBeautify(false);
        if (php_sapi_name() == 'cli') {
        $oBatch->show();
        } else {
            //echo '<pre>'.$oBatch->show() .'</pre>';
        }
        // php_beautifier->setBeautify(true);
        
    }
    catch(Exception $oExp) {
        echo ($oExp);
    }
?>