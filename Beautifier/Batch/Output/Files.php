<?php
    /* vim: set expandtab tabstop=4 shiftwidth=4: */
    // +----------------------------------------------------------------------+
    // | PHP version 5                                                        |
    // +----------------------------------------------------------------------+
    // | Copyright (c) 1997-2004 The PHP Group                                |
    // +----------------------------------------------------------------------+
    // | This source file is subject to version 3.0 of the PHP license,       |
    // | that is bundled with this package in the file LICENSE, and is        |
    // | available through the world-wide-web at the following url:           |
    // | http://www.php.net/license/3_0.txt.                                  |
    // | If you did not receive a copy of the PHP license and are unable to   |
    // | obtain it through the world-wide-web, please send a note to          |
    // | license@php.net so we can mail you a copy immediately.               |
    // +----------------------------------------------------------------------+
    // | Authors: Claudio Bustos <cdx@users.sourceforge.net>                  |
    // |          Jens Bierkandt <schtorch@users.sourceforge.net>             |
    // +----------------------------------------------------------------------+
    //
    // $Id:
    /**
    * Definition for PHP_Beautifier_Batch_Files
    * @package PHP_Beautifier
    * @subpackage Batch
    */
    /**
    * PHP_Beautifier_Batch_Files
    * Handle the batch process for one/multiple php files to one out
    * @package PHP_Beautifier
    * @subpackage Batch
    */
    class PHP_Beautifier_Batch_Output_Files extends PHP_Beautifier_Batch_Output {
        public function get() 
        {
            $aInputFiles = $this->oBatch->getInputFiles();
            if (count($aInputFiles) == 1) {
                $this->beautifierSetInputFile(reset($aInputFiles));
                $this->beautifierProcess();
                return $this->beautifierGet();
            } else {
                $sText = '';
                foreach($aInputFiles as $sFile) {
                    $this->beautifierSetInputFile($sFile);
                    $this->beautifierProcess();
                    $sText.= $this->getWithHeader($sFile);
                }
                return $sText;
            }
        }
        private function getWithHeader($sFile) 
        {
            $sNewLine = $this->oBatch->callBeautifier($this, 'getNewLine');
            $sHeader = '- BEGIN OF '.$sFile.' -'.$sNewLine;
            $sLine = str_repeat('-', strlen($sHeader) -1) .$sNewLine;
            $sEnd = '- END OF '.$sFile.str_repeat(' ', strlen($sHeader) -strlen($sFile) -12) .' -'.$sNewLine;
            $sText = $sLine.$sHeader.$sLine.$sNewLine;
            $sText.= $this->beautifierGet();
            $sText.= $sNewLine.$sLine.$sEnd.$sLine.$sNewLine;
            return $sText;
        }
        public function save() 
        {
            $sFile = $this->oBatch->getOutputPath();
            if ($sFile == STDOUT) {
                $fp = STDOUT;
            } else {
                $fp = fopen($this->oBatch->getOutputPath() , "w");
            }
            if (!$fp) {
                throw(new Exception("Can't save file $sFile"));
            }
            $sText = $this->get();
            fputs($fp, $sText, strlen($sText));
            if ($fp != STDOUT) {
                fclose($fp);
            }
            return true;
        }
    }
?>