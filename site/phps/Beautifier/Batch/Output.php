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
    * @package PHP_Beautifier
    * @subpackage Batch
    */
    /**
    * PHP_Beautifier_Batch_Output
    * @package PHP_Beautifier
    * @subpackage Batch
    */
    abstract class PHP_Beautifier_Batch_Output {
        protected $oBatch;
        public function __construct(PHP_Beautifier_Batch $oBatch) {
            $this->oBatch = $oBatch;
        }
        protected function beautifierSetInputFile($sFile) {
            return $this->oBatch->callBeautifier($this, 'setInputFile', array(
                $sFile
            ));
        }
        protected function beautifierProcess() {
            return $this->oBatch->callBeautifier($this, 'process');
        }
        protected function beautifierGet() {
            return $this->oBatch->callBeautifier($this, 'get');
        }
        protected function beautifierSave($sFile) {
            return $this->oBatch->callBeautifier($this, 'save', array(
                $sFile
            ));
        }
        public function get() {
        }
        public function save() {
        }
    }
?>