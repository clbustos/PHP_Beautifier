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
    * @subpackage Tokenizer
    */
    /**
    * Interface for Tokenizer
    * 
    * In the constructor, you should send the text of the file / code
    * The function getTokens() should send the tokens for the code, like
    * token_get_all() 
    * @package PHP_Beautifier
    * @subpackage Tokenizer
    */
    interface PHP_Beautifier_Tokeniker_Interface {
        public function __construct($sText);
        public function getTokens();
    }
?>
