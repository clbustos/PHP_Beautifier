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
    * @subpackage StreamWrapper
    */
    /**
    * Interface for StreamWrapper
    * Read the documentation for streams wrappers on php manual.
    * @package PHP_Beautifier
    * @subpackage StreamWrapper
    */
    interface PHP_Beautifier_StreamWrapper_Interface {
        function stream_open($sPath, $sMode, $iOptions, &$sOpenedPath);
        function stream_close();
        function stream_read($iCount);
        function stream_write($sData);
        function stream_eof();
        function stream_tell();
        function stream_seek($iOffset, $iWhence);
        function stream_flush();
        function stream_stat();
        function unlink($sPath);
        function dir_opendir($sPath, $iOptions);
        function dir_readdir();
        function dir_rewinddir();
        function dir_closedir();
    }
    require_once('StreamWrapper/Tarz.php');
?>