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
    * Definition of class PHP_Beautifier_Filter_Pear
    * @package PHP_Beautifier
    * @subpackage Filter
    */
    /**
    * Require PEAR_Config
    */
    require_once ('PEAR/Config.php');
    /**
    * Filter the code to make it compatible with PEAR Coding Standars
    *
    * The default filter, {@link PHP_Beautifier_Filter_Default} have most of the specs
    * but adhere more to GNU C.
    * So, this filter make the following modifications:
    * - Add 2 newlines after Break in switch statements
    * - Brace in function definition put on a new line, same indent of 'function' construct
    * - Comments started with '#' are replaced with '//'
    * - Open tags are replaced with '<?php'
    * - With setting 'add_header', the filter add one of the standard PEAR comment header
    *   (php, bsd, apache, lgpl) or any file as licence header. Use:
    * <code>
    * $oBeaut->addFilter('Pear',array('add_header'=>'php'));
    * </code>
    * @link http://pear.php.net/manual/en/standards.php
    * @package PHP_Beautifier
    * @subpackage Filter
    */
    class PHP_Beautifier_Filter_Pear extends PHP_Beautifier_Filter {
        protected $aSettings = array('add_header'=>false);
        protected $sDescription = 'Filter the code to make it compatible with PEAR Coding Specs';
        function t_semi_colon($sTag) 
        {
            if (!$this->oBeaut->isPreviousTokenConstant(T_BREAK)) {
                return PHP_Beautifier_Filter::BYPASS;
            }
            $this->oBeaut->removeWhitespace();
            $this->oBeaut->add($sTag);
            $this->oBeaut->addNewLine();
            $this->oBeaut->addNewLineIndent();
        }
        function t_open_brace($sTag) 
        {
            if (!$this->oBeaut->getMode('function')) {
                return PHP_Beautifier_Filter::BYPASS;
            }
            $this->oBeaut->addNewLineIndent();
            $this->oBeaut->add($sTag);
            $this->oBeaut->incIndent();
            $this->oBeaut->addNewLineIndent();
        }
        function t_comment($sTag) 
        {
            if ($sTag{0} != '#') {
                return PHP_Beautifier_Filter::BYPASS;
            }
            $oFilterDefault = new PHP_Beautifier_Filter_Default($this->oBeaut);
            $sTag = '//'.substr($sTag, 1);
            return $oFilterDefault->t_comment($sTag);
        }
        function t_open_tag($sTag) 
        {
            static $bOpenTag = false;
            // find PEAR header comment
            $this->oBeaut->add("<?php");
            $this->oBeaut->addNewLineIndent();
            if (!$bOpenTag) {
                $bOpenTag = true;
                // store the comment and search for word 'license'
                $sComment = '';
                $x = 1;
                while ($this->oBeaut->isNextTokenConstant(T_COMMENT, $x)) {
                    $sComment.= $this->oBeaut->getNextTokenContent($x);
                    $x++;
                }
                if (stripos($sComment, 'license') === FALSE) {
                    $this->addHeaderComment();
                }
            }
        }
        function addHeaderComment() 
        {
            if (!($sLicense = $this->getSetting('add_header'))) {
                return;
            }
            // if Header is a path, try to load the file
            if (file_exists($sLicense)) {
                $sDataPath = $sLicense;
            } else {
                $oConfig = PEAR_Config::singleton();
                $sDataPath = PHP_Beautifier_Common::normalizeDir($oConfig->get('data_dir')) .'PHP_Beautifier/licenses/'.$sLicense.'.txt';
            }
            if (file_exists($sDataPath)) {
                $sLicenseText = file_get_contents($sDataPath);
            } else {
                throw(new Exception("Can't load license '".$sLicense."'"));
            }
            $this->oBeaut->removeWhitespace();
            $this->oBeaut->addNewLine();
            $this->oBeaut->add($sLicenseText);
            $this->oBeaut->addNewLineIndent();
        }
    }
?>