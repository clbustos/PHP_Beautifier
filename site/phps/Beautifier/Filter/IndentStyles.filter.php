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
    * Definition of class PHP_Beautifier_Filter_IndentStyles
    * @package PHP_Beautifier
    * @subpackage Filter
    */
    /**
    * Indent the code in 4 styles.
    *
    * Use 'style' setting to select the style. You can change the style inside
    * the file, using the callbacks features.
    * The following is a description taken from {@link http://catb.org/~esr/jargon/html/I/indent-style.html }
    *
    * K&R style <b>['k&r']</b> Named after Kernighan & Ritchie, because the examples in K&R are formatted this way. Also called kernel style because the Unix kernel is written in it, and the "One True Brace Style" (abbrev. 1TBS) by its partisans. In C code, the body is typically indented by eight spaces (or one tab) per level, as shown here. Four spaces are occasionally seen in C, but in C++ and Java four tends to be the rule rather than the exception.
    *
    *<CODE>
    *if (<cond>) {
    *   <body>
    *   }
    *</CODE>
    *
    *   Allman style <b>['allman' or 'bsd']</b> Named for Eric Allman, a Berkeley hacker who wrote a lot of the BSD utilities in it (it is sometimes called BSD style). Resembles normal indent style in Pascal and Algol. It is the only style other than K&R in widespread use among Java programmers. Basic indent per level shown here is eight spaces, but four (or sometimes three) spaces are generally preferred by C++ and Java programmers.
    *
    *<CODE>
    *   if (<cond>)
    *   {
    *        <body>
    *   }
    * </CODE>
    *
    *
    * Whitesmiths style <b>['whitesmiths']</b>? popularized by the examples that came with Whitesmiths C, an early commercial C compiler. Basic indent per level shown here is eight spaces, but four spaces are occasionally seen.
    *
    * <CODE>
    * if (<cond>)
    *     {
    *     <body>
    *     }
    * </CODE>
    *
    * GNU style <b>['gnu']</b>  Used throughout GNU EMACS and the Free Software Foundation code, and just about nowhere else. Indents are always four spaces per level, with { and } halfway between the outer and inner indent levels.
    *
    *<CODE>
    * if (<cond>)
    *  {
    *    <body>
    *  }
    * </CODE>
    *
    * @package PHP_Beautifier
    * @subpackage Filter
    */
    class PHP_Beautifier_Filter_IndentStyles extends PHP_Beautifier_Filter {
        protected $aSettings = array(
            'style'=>'K&R'
        );
        public $aAllowedStyles = array(
            "k&r"=>"kr",
            "allman"=>"bsd",
            "bsd"=>"bsd",
            "gnu"=>"gnu",
            "whitesmiths"=>"ws",
            "ws"=>"ws"
        );
        protected $sDescription = 'Filter the code in 4 different indent styles: K&R, Allman, Whitesmiths and GNU';
        public function __construct(PHP_Beautifier $oBeaut, $aSettings = array()) {
            parent::__construct($oBeaut, $aSettings);
            $this->addSettingDefinition('style', 'text', 'Style for indent: K&R, Allman, Whitesmiths, GNU');
        }
        public function __call($sMethod, $aArgs) {
            if (strtolower($this->getSetting['style']) == 'k&r') {
                return PHP_Beautifier_Filter::BYPASS;
            }
            $sNewMethod = $this->_getFunctionForStyle($sMethod);
            if (method_exists($this, $sNewMethod)) {
                call_user_func_array(array(
                    $this,
                    $sNewMethod
                ) , $aArgs);
            } else {
                return PHP_Beautifier_Filter::BYPASS;
            }
        }
        /**
        * Open braces in BSD style
        * @param    string  '{'
        */
        function t_open_brace_bsd($sTag) {
            $this->oBeaut->addNewLineIndent();
            $this->oBeaut->add($sTag);
            $this->oBeaut->incIndent();
            $this->oBeaut->addNewLineIndent();
        }
        /**
        * Close braces in BSD style
        * @param    string  '}'
        */
        function t_close_brace_bsd($sTag) {
            if ($this->oBeaut->getMode('string_index') or $this->oBeaut->getMode('double_quote')) {
                $this->oBeaut->add($sTag);
            } else {
                $this->oBeaut->removeWhitespace();
                $this->oBeaut->decIndent();
                $this->oBeaut->addNewLineIndent();
                $this->oBeaut->add($sTag);
                $this->oBeaut->addNewLineIndent();
            }
        }
        /**
        * Open braces in Whitesmiths style
        * @param    string  '{'
        */
        function t_open_brace_ws($sTag) {
            $this->oBeaut->addNewLine();
            $this->oBeaut->incIndent();
            $this->oBeaut->addIndent();
            $this->oBeaut->add($sTag);
            $this->oBeaut->addNewLineIndent();
        }
        /**
        * Close braces in Whitesmiths style
        * @param    string  '}'
        */
        function t_close_brace_ws($sTag) {
            if ($this->oBeaut->getMode('string_index') or $this->oBeaut->getMode('double_quote')) {
                $this->oBeaut->add($sTag);
            } else {
                $this->oBeaut->removeWhitespace();
                $this->oBeaut->addNewLineIndent();
                $this->oBeaut->add($sTag);
                $this->oBeaut->decIndent();
                $this->oBeaut->addNewLineIndent();
            }
        }
        /**
        * Close braces in GNU style
        * @param    string  '}'
        */
        function t_close_brace_gnu($sTag) {
            if ($this->oBeaut->getMode('string_index') or $this->oBeaut->getMode('double_quote')) {
                $this->oBeaut->add($sTag);
            } else {
                $iHalfSpace = floor($this->oBeaut->iIndentNumber/2);
                $this->oBeaut->removeWhitespace();
                $this->oBeaut->decIndent();
                $this->oBeaut->addNewLineIndent();
                $this->oBeaut->add(str_repeat($this->oBeaut->sIndentChar, $iHalfSpace));
                $this->oBeaut->add($sTag);
                $this->oBeaut->addNewLineIndent();
            }
        }
        /**
        * Open braces in GNU style
        * @param    string  '{'
        */
        function t_open_brace_gnu($sTag) {
            $iHalfSpace = floor($this->oBeaut->iIndentNumber/2);
            $this->oBeaut->addNewLineIndent();
            $this->oBeaut->add(str_repeat($this->oBeaut->sIndentChar, $iHalfSpace));
            $this->oBeaut->add($sTag);
            $this->oBeaut->incIndent();
            $this->oBeaut->addNewLineIndent();
        }
        /**
        * Else for bds, gnu & ws
        * @param    string  else or elseif
        * @return   void|PHP_Beautifier_Filter::BYPASS
        */
        function t_else($sTag) {
            if ($this->oBeaut->getPreviousTokenContent() == '}') {
                $this->oBeaut->removeWhitespace();
                $this->oBeaut->addNewLineIndent();
                $this->oBeaut->add(trim($sTag));
            } else {
                return PHP_Beautifier_Filter::BYPASS;
            }
        }
        /**
        * Return the method for the defined style
        * @param    string  method to search
        * @return   string  method renamed for the defined style
        */
        private function _getFunctionForStyle($sMethod) {
            $sStyle = strtolower($this->getSetting('style'));
            if (!array_key_exists($sStyle, $this->aAllowedStyles)) {
                throw (new Exception("Style ".$sStyle."doesn't exists"));
            }
            return $sMethod."_".$this->aAllowedStyles[$sStyle];
        }
    }
?>