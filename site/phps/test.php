<?php
    error_reporting(E_ALL|E_STRICT);
    require_once ('./Beautifier.php');
    require_once ('./Beautifier/Batch.php');
    try {
        $oBeaut = new PHP_Beautifier();
        $oBatch = new PHP_Beautifier_Batch($oBeaut);
        //$oBatch->setFileType('js');
        //$oBatch->addFilter('ArrayNested');
        //$oBatch->addFilter('ListClassFunction');
        $oBatch->addFilter('IndentStyles', array(
            'style'=>'gnu'
        ));
        //$oBatch->setRecursive(true);
        unlink('test2.php');
        $oBatch->setInputFile('./site/*.php');
        $oBatch->setOutputFile('test2.php');
        //$oBatch->setCompress('gz');
        $oBatch->process();
        $oBatch->save();
        // php_beautifier->setBeautify(false);
        if (php_sapi_name() == 'cli') {
        //$oBatch->show();
        //$oBatch->save();
        } else {
            //echo '<pre>'.$oBatch->show() .'</pre>';
        }
        // php_beautifier->setBeautify(true);
        
    }
    catch(Exception $oExp) {
        echo ($oExp);
    }
?>