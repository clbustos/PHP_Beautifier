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
    * Definition for PHP_Beautifier_Batch_FilesGz
    * @package PHP_Beautifier
    * @subpackage Batch
    */
    /**
    * Require Archive_Tar
    */
    include_once 'Archive/Tar.php';
    /**
    * PHP_Beautifier_Batch_Output_FilesTar
    * Handle the batch process for one/multiple php files to one tar gzipped file
    * @package PHP_Beautifier
    * @subpackage Batch
    */
    abstract class PHP_Beautifier_Batch_Output_FilesTar extends PHP_Beautifier_Batch_Output {
        protected $oTar;
        protected $sCompress;
        public function __construct(PHP_Beautifier_Batch $oBatch) {
            parent::__construct($oBatch);
            $sOutput = $this->oBatch->getOutputPath();
            $sOutput = preg_replace("/(\.tar\.gz|\.tgz|\.gz|\.tar\.bz2)$/", '', $sOutput) .".tgz";
            PHP_Beautifier_Common::createDir($sOutput);
            $this->oTar = new Archive_Tar($sOutput, $this->sCompress);
        }
        public function get() {
            throw (new Exception("TODO"));
        }
        public function save() {
            $aInputFiles = $this->oBatch->getInputFiles();
            $aOutputFiles = PHP_Beautifier_Common::getSavePath($aInputFiles);
            for ($x = 0;$x<count($aInputFiles);$x++) {
                $this->beautifierSetInputFile($aInputFiles[$x]);
                $this->beautifierProcess();
                $this->oTar->addString($aOutputFiles[$x], $this->beautifierGet());
            }
        }
    }
?>