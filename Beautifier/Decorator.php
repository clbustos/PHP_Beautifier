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
    */
    /**
    * PHP_Beautifier_Decorator.
    * @package PHP_Beautifier
    */
    abstract class PHP_Beautifier_Decorator implements PHP_Beautifier_Interface {
        protected $oBeaut;
        function __construct(PHP_Beautifier_Interface $oBeaut) 
        {
            $this->oBeaut = $oBeaut;
        }
        function __call($sMethod, $aArgs) 
        {
            if (!method_exists($this->oBeaut, $sMethod)) {
                throw (new Exception("Method '$sMethod' doesn't exists"));
            } else {
                return call_user_func_array(array(
                    $this->oBeaut,
                    $sMethod
                ) , $aArgs);
            }
        }
    }
?>