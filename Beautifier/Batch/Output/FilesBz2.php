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
    * Definition for PHP_Beautifier_Batch_FilesBz2
    * @package PHP_Beautifier
    * @subpackage Batch
    */
    /**
    * Include PHP_Beautifier_Batch_FilesGz
    */    
    require_once 'FilesTar.php';
    /**
    * PHP_Beautifier_Batch_FilesBz2
    * Handle the batch process for one/multiple php files to one tar bzip2 file
    * @package PHP_Beautifier
    * @subpackage Batch
    */
    class PHP_Beautifier_Batch_Output_FilesBz2 extends PHP_Beautifier_Batch_Output_FilesTar {
        protected $sCompress='bz2';
    }
?>