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
    require_once "PHPUnit.php";
	if (file_exists('../Beautifier.php')) {
		include_once '../Beautifier.php';
	} else {
		include_once "PHP/Beautifier.php";
	}
    class Beautifier_Common_TestCase extends PHPUnit_TestCase {
        function Beautifier_Common_TestCase($name) 
        {
            $this->PHPUnit_TestCase($name);
        }
        function testnormalizeDir() 
        {
            $sDir = str_replace(DIRECTORY_SEPARATOR, '/', dirname(__FILE__)) .'/';
            $this->assertEquals($sDir, PHP_Beautifier_Common::normalizeDir(dirname(__FILE__)));
        }
        function testgetFilesByPattern() 
        {
            $sDir = PHP_Beautifier_Common::normalizeDir(dirname(__FILE__));
            $aExpected = array(
                $sDir.'Beautifier.phpt'
            );
            $this->assertEquals($aExpected, PHP_Beautifier_Common::getFilesByPattern($sDir, 'Beautifier\.....', false));
        }
        function testgetFilesByGlob() 
        {
            $sDir = PHP_Beautifier_Common::normalizeDir(dirname(__FILE__));
            $aExpected = array(
                $sDir.'Beautifier_Common.phpt'
            );
            $this->assertEquals($aExpected, PHP_Beautifier_Common::getFilesByGlob($sDir.basename(__FILE__, '.phpt') .'.????', false));
        }
        function testWsToString() {
            $this->assertEquals(' \t\r\n',PHP_Beautifier_Common::wsToString(" \t\r\n"));
        }

    }
    $suite = new PHPUnit_TestSuite('Beautifier_Common_TestCase');
    $result = PHPUnit::run($suite);
    echo $result->toString();
?>