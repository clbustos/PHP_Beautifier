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
    * Definition of class PHP_Beautifier_Filter_ArrayNested
    * @package PHP_Beautifier
    * @subpackage Filter
    */
    /**
    * Indent the array structures
    * Ex.
    * <CODE>
    *    $aMyArray = array(
    *        array(
    *            array(
    *                array(
    *                    'el'=>1,
    *                    'el'=>2
    *                )
    *            )
    *        )
    *    );
    * </CODE>
    * @package PHP_Beautifier
    * @subpackage Filter
    */
    class PHP_Beautifier_Filter_ArrayNested extends PHP_Beautifier_Filter {
        public function t_parenthesis_open($sTag) 
        {
            $this->oBeaut->add($sTag);
            if ($this->oBeaut->getControlParenthesis()==T_ARRAY) {
                $this->oBeaut->addNewLine();
                $this->oBeaut->incIndent();
                $this->oBeaut->addIndent();
            }
        }
        public function t_parenthesis_close($sTag) 
        {
            $this->oBeaut->removeWhitespace();
            if ($this->oBeaut->getControlParenthesis()==T_ARRAY) {
                $this->oBeaut->decIndent();
                if ($this->oBeaut->getPreviousTokenContent()!='(') {
                    $this->oBeaut->addNewLine();
                    $this->oBeaut->addIndent();
                }
                $this->oBeaut->add($sTag.' ');
            } else {
                $this->oBeaut->add($sTag.' ');
            }
        }
        public function t_comma($sTag) 
        {
            if ($this->oBeaut->getControlParenthesis()!=T_ARRAY) {
                $this->oBeaut->add($sTag.' ');
            } else {
                $this->oBeaut->add($sTag);
                $this->oBeaut->addNewLine();
                $this->oBeaut->addIndent();
            }
        }

    }
?>