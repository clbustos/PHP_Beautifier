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
    * Definition for PHP_Beautifier_Batch_Directory
    * @package PHP_Beautifier
    * @subpackage Batch
    */
    /**
    * Include Archive_Tar
    */
    include_once 'Archive/Tar.php';    
    /**
    * PHP_Beautifier_BatchTar
    * Handle the batch process for multiple php files to one directory.
    *
    * @package PHP_Beautifier
    * @subpackage Batch
    */
    abstract class PHP_Beautifier_Batch_Output_DirectoryTar extends PHP_Beautifier_Batch_Output {
        public function save() 
        {
            $aInputFiles = $this->oBatch->getInputFiles();
            $sOutputPath = $this->oBatch->getOutputPath();
            $aOutputFiles = PHP_Beautifier_Common::getSavePath($aInputFiles, $sOutputPath);
            for ($x = 0;$x<count($aInputFiles);$x++) {
                unset($oTar);
                $oTar=$this->getTar($aOutputFiles[$x]);
                $this->beautifierSetInputFile($aInputFiles[$x]);
                $this->beautifierProcess();
                PHP_Beautifier_Common::createDir($aOutputFiles[$x]);
                $oTar->addString(basename($aOutputFiles[$x]),$this->beautifierGet());
            }
            return true;
        }
        /**
        * @todo implements this
        */
        protected function getTar($sFileName) {
        }
    }
?>