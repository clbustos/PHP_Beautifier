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
	# use pear or local version of php_beautifier
	if (file_exists('../Beautifier.php')) {
		include_once '../Beautifier.php';
	} else {
		include_once "PHP/Beautifier.php";
	}
    // Mock Filter object
    class Test_Filter extends PHP_Beautifier_Filter {
        public $aTokens = array();
        public $aModes = array();
        public $iIndex = 0;
        function handleToken($token) 
        {                
            $this->oBeaut->add($token[1]);
            $this->aTokens[] = $token;
            foreach($this->oBeaut->aModesAvailable as $sMode) {
                $this->aModes[$this->iIndex][$sMode] = $this->oBeaut->getMode($sMode);
            }
            $this->iIndex++;
        }
    }
    class Beautifier_TestCase extends PHPUnit_TestCase {
        function __construct($name) 
        {
            $this->PHPUnit_TestCase($name);
        }
        function setUp() 
        {
            $this->oBeaut = new PHP_Beautifier();
            $this->oBeaut->setInputFile(__FILE__);
        }
        function testsetInputFile() 
        {
            $this->assertTrue($this->oBeaut->setInputFile(__FILE__));
            try {
                $this->assertFalse($this->oBeaut->setInputFile('NO_FILE'));
            }
            catch(Exception $oExp) {
                $this->assertTrue($oExp instanceof Exception);
            }
        }
        function testaddFilterDirectory() 
        {
            $sDir = PHP_Beautifier_Common::normalizeDir(dirname(__FILE__));
            $this->oBeaut->addFilterDirectory($sDir);
            $aDirs = $this->oBeaut->getFilterDirectories();
            $this->assertEquals(end($aDirs) , $sDir);
        }
        function testgetFilterList() 
        {
            $aFilterList = array(
                'Default'
            );
            $this->assertEquals($aFilterList, $this->oBeaut->getFilterList());
        }
        function testaddFilter() 
        {
            if(file_exists('../Beautifier/Filter/ArrayNested.filter.php')) {
                include_once('../Beautifier/Filter/ArrayNested.filter.php');
            } else {
            include_once ('PHP/Beautifier/Filter/ArrayNested.filter.php');

            }            // Include filter by string
            $this->oBeaut->addFilter('Pear');
            $aFilterList = array(
                'Pear',
                'Default'
            );
            $this->assertEquals($aFilterList, $this->oBeaut->getFilterList());
            // include filter by object
            $oFilter = new PHP_Beautifier_Filter_ArrayNested($this->oBeaut);
            $this->oBeaut->addFilter($oFilter);
            array_unshift($aFilterList, 'ArrayNested');
            $this->assertEquals($aFilterList, $this->oBeaut->getFilterList());
            try {
                $this->oBeaut->addFilter('Error');
            }
            catch(Exception $oExp) {
                $this->assertTrue($oExp instanceof Exception_PHP_Beautifier_Filter);
            }
        }
        public function testgetFilterListTotal() 
        {
            $aEspFilters = array(
                'Default',
                'ListClassFunction',
                'NewLines',
                'Pear',
                'ArrayNested',
                'IndentStyles',
                'Lowercase'
            );
            sort($aEspFilters);
            $aRealFilters = $this->oBeaut->getFilterListTotal();
            $this->assertEquals($aEspFilters, $aRealFilters);
        }
        function testsetIndentChar() 
        {
            $this->oBeaut->setIndentChar('*');
            $this->assertEquals('*', $this->oBeaut->sIndentChar);
        }
        function testsetIndentNumber() 
        {
            $this->oBeaut->setIndentNumber('5');
            $this->assertEquals(5, $this->oBeaut->iIndentNumber);
        }
        function testsetNewLine() 
        {
            $this->oBeaut->setNewLine("\r\n");
            $this->assertEquals("\r\n", $this->oBeaut->sNewLine);
        }
        function testsetOutputFile() 
        {
            $this->oBeaut->setOutputFile('test.php');
            $this->assertEquals("test.php", $this->oBeaut->sOutputFile);
        }
        function testsetMode() 
        {
            $this->oBeaut->setMode('test');
            $this->assertTrue($this->oBeaut->getMode('test'));
        }
        function testunsetMode() 
        {
            $this->oBeaut->unsetMode('test');
            $this->assertFalse($this->oBeaut->getMode('test'));
        }
        function testGetFilterDescription() 
        {
            $oFilter = new PHP_Beautifier_Filter_Default($this->oBeaut);
            $this->assertEquals($this->oBeaut->getFilterDescription('Default') , $oFilter);
        }
        function testsave() 
        {
            $sTempFile = tempnam("/tmp", "PHP_TEST");
            $this->oBeaut->process();
            $this->oBeaut->save($sTempFile);
            $this->assertEquals(preg_replace("/\s/", "", file_get_contents(__FILE__)) , preg_replace("/\s/", "", file_get_contents($sTempFile)));
            @unlink($sTempFile);
        }
        function testget() 
        {
            $this->oBeaut->process();
            $this->assertEquals(preg_replace("/\s/", "", file_get_contents(__FILE__)) , preg_replace("/\s/", "", $this->oBeaut->get()));
        }
        function testComments() 
        {
            $sTextOriginal = <<<SCRIPT
<?php
// short comment
/* Long comment */
\$a=5;                 // short comment 2
\$b=6;                 /* long comment 2 */
/*
Long comment 3
*/
?>
SCRIPT;
            $sTextExpected = <<<SCRIPT
<?php
// short comment
/* Long comment */
\$a = 5; // short comment 2
\$b = 6; /* long comment 2 */
/*
Long comment 3
*/
?>
SCRIPT;
            $sTextExpected = str_replace("\r\n", "\n", $sTextExpected);
            $this->oBeaut->setInputString($sTextOriginal);
            $this->oBeaut->process();
            $sTextActual = $this->oBeaut->get();
            /*for ($x = 0;$x<strlen($sTextExpected);$x++) {
                $this->assertEquals($sTextExpected{$x}, $sTextActual{$x});
            }*/
                $this->assertEquals($sTextExpected, $sTextActual);

        }
function testDocComment() 
        {
            $sTextOriginal = <<<SCRIPT
<?php
/** Doc comment inline */
                      /** First line
  * Other line
                   */
/**
       * Doc normal comment
 *
            * Other line
 */
?>
SCRIPT;
            $sTextExpected = <<<SCRIPT
<?php
/** Doc comment inline */
/** First line
 * Other line
 */
/**
 * Doc normal comment
 *
 * Other line
 */
?>
SCRIPT;
            $this->oBeaut->setInputString($sTextOriginal);
            $this->oBeaut->process();
            $this->assertEquals($sTextExpected,$this->oBeaut->get());
        }
        function testNestedSwitch() {
$sTextOriginal = <<<SCRIPT
<?php
switch(\$a) {
    case 1:
    case 2:
    switch(\$b) {
        case 1:
        case 2:
            switch(\$c) {
                case 1:
                case 2:
                    echo "hola";
                break;
                default:
                    echo "leso";
                break;
            }
        break;
        case 3:
            echo "hola";
        break;
    }
    break;
}
?>
SCRIPT;
            $sTextExpected = <<<SCRIPT
<?php
switch (\$a) {
    case 1:
    case 2:
        switch (\$b) {
            case 1:
            case 2:
                switch (\$c) {
                    case 1:
                    case 2:
                        echo "hola";
                    break;
                    default:
                        echo "leso";
                    break;
                }
            break;
            case 3:
                echo "hola";
            break;
        }
    break;
}
?>
SCRIPT;
            $this->oBeaut->setInputString($sTextOriginal);
            $this->oBeaut->process();
            $this->assertEquals($sTextExpected,$this->oBeaut->get());            
        
        }
        /*
        function testresetProperties() {
        }
        
        function testgetTokenAssoc() {
        }
        function testgetTokenAssocText() {
        }
        function testreplaceTokenAssoc() {
        }
        function testgetTokenFunction() {
        }
        function testcontrolToken() {
        }
        function testcontrolTokenPost() {
        }
        function testpushControlSeq() {
        }
        function testpopControlSeq() {
        }
        function testpushControlParenthesis() {
        }
        function testpopControlParenthesis() {
        }
        function testsetBeautify() {
        }
        function testshow() {
        }
        
        function testgetSetting() {
        }
        function testgetControlSeq() {
        }
        function testgetControlParenthesis() {
        }
        
        function testgetMode() {
        }
        function testadd() {
        }
        function testpop() {
        }
        function testaddNewLine() {
        }
        function testaddIndent() {
        }
        function testgetPreviousToken() {
        }
        function testgetPreviousTokenConstant() {
        }
        function testgetPreviousTokenContent() {
        }
        function testgetNextToken() {
        }
        function testgetNextTokenConstant() {
        }
        function testgetNextTokenContent() {
        }
        function testremoveWhitespace() {
        }
        */
    }
    class BeautifierInternal_TestCase extends PHPUnit_TestCase {
        public $oBeaut;
        public $oFilter;
        function __construct($name) 
        {
            $this->PHPUnit_TestCase($name);
        }
        function setUp() 
        {
            $this->oBeaut = new PHP_Beautifier();
            $this->oFilter = new Test_Filter($this->oBeaut);
            $this->oBeaut->addFilter($this->oFilter);
        }
        function setText($sText) 
        {
            $this->oBeaut->setInputString($sText);
            $this->oBeaut->process();
        }
        function testSetBeautify() 
        {
            $sText = <<<SCRIPT
<?php
echo "sdsds";
// php_beautifier->setBeautify(false);
echo "don't process this token";
// php_beautifier->on(true);

?>
SCRIPT;
            $this->setText($sText);
            // verify setBeautify
            $this->assertTrue(count($this->oFilter->aTokens) == 6);
        }
        function testModes() 
        {
            $sText = <<<SCRIPT
<?php
\$a=(\$b>1)?'0':'2';
\$b="asa{\$a}";
?>
SCRIPT;
            //php_beautifier->seatBeautify(true);
            $this->setText($sText);
            // ternary mode from ? to :
            $this->assertFalse($this->oFilter->aModes[7]['ternary_operator']);
            for ($x = 8;$x <= 10;$x++) {
                $this->assertTrue($this->oFilter->aModes[$x]['ternary_operator'], $x);
            }
            $this->assertFalse($this->oFilter->aModes[15]['double_quote']);
            // quote from " to } previous to "
            for ($x = 16;$x <= 20;$x++) {
                $this->assertTrue($this->oFilter->aModes[$x]['double_quote'], $x);
            }
        }
    }
    $suite = new PHPUnit_TestSuite('Beautifier_TestCase');
    $result = PHPUnit::run($suite);
    echo $result->toString();
    $suite = new PHPUnit_TestSuite('BeautifierInternal_TestCase');
    $result = PHPUnit::run($suite);
    echo $result->toString();
?>