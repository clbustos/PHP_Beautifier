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
class Beautifier_Bugs extends PHPUnit_TestCase {
    function Beautifier_Bugs($name) 
    {
        $this->PHPUnit_TestCase($name);
    }
    function setUp() 
    {
        $this->oBeaut = new PHP_Beautifier();
    }
    function setText($sText) 
    {
        $this->oBeaut->setInputString($sText);
        $this->oBeaut->process();
    }
    /**
    * HeredocBeforeCloseTag
    * Close tag after heredoc remove whitespace,
    * breaking the script.
    *
    */
    function testBugInternal1() 
    {
        $sText = <<<SCRIPT
<?php
\$a = <<<HEREDOC
sdsdsds
HEREDOC;
?>
SCRIPT;
        $this->setText($sText);
        $sActual = $this->oBeaut->get();
        $this->assertTrue(preg_match("/HEREDOC;\n\s*?\?>/ms", $sActual));
    }
    /**
    * Bug 1597
    * Brace after short comment in new line was appended to
    * the comment, breaking the code
    */
    function testBug1597() 
    {
        $sText = <<<SCRIPT
<?php
if (\$_POST["url"] != "") //inserting data
{
}
?>
SCRIPT;
        $this->setText($sText);
        $sExpected = <<<SCRIPT
<?php
if (\$_POST["url"] != "") //inserting data
{
}
?>
SCRIPT;
        $this->assertEquals($sExpected, $this->oBeaut->get());
    }
    /**
    * Bug 2301
    * When I try to beautify PHP5 code with the 'throw new Exception'
    * statement, the code is not formatted correctly. The
    * whitespace between throw AND new is deleted.
    */
    function testBug2301() 
    {
        $sText = <<<SCRIPT
<?php
throw new AccountFindException();
?>
SCRIPT;
        $this->setText($sText);
        $sExpected = <<<SCRIPT
<?php
throw new AccountFindException();
?>
SCRIPT;
        $this->assertEquals($sExpected, $this->oBeaut->get());
    }
    /**
    * Bug from Pavel Chtchevaev, 2004-11-17
    * There's one more issue about the default filter, try beautifying with
    * it the following string:
    * "<?php\n\$o->_test1(\$c->test2()->test3())\n?>"
    * It will return:
    * "<?php\n    \$o->_test1(\$c->test2() ->test3())\n?>"
    */
    function testBugChtchevaev_2004_11_17() 
    {
        $sText = <<<SCRIPT
<?php
\$o->_test1(\$c-> test2()-> test3());
?>
SCRIPT;
        $this->setText($sText);
        $sExpected = <<<SCRIPT
<?php
\$o->_test1(\$c->test2()->test3());
?>
SCRIPT;
        $this->assertEquals($sExpected, $this->oBeaut->get());
    }
    /**
    * Bug 3257
    * Comments between if and elseif screws up formatting.
    * The beautifier will cascade and start moving the indentations over
    * if there is a comment between if {} and elseif {}
    */
    function testBug3257() 
    {
        $sText = <<<SCRIPT
<?php
    class Foo {
        var \$foobar = 0;
        function Foo(\$a, \$b) {
            if (\$a) {
                dostuff();
            }
            // \$a no good
            elseif {
                dootherstuff();
            }
            // \$c maybe
            elseif {
                yea();
            }
        }

        function bar() {
            echo "Hello";
        }
    }

?>
SCRIPT;
        $this->setText($sText);
        $sExpected = <<<SCRIPT
<?php
class Foo {
    var \$foobar = 0;
    function Foo(\$a, \$b) {
        if (\$a) {
            dostuff();
        }
        // \$a no good
        elseif {
            dootherstuff();
        }
        // \$c maybe
        elseif {
            yea();
        }
    }
    function bar() {
        echo "Hello";
    }
}
?>
SCRIPT;
        $this->assertEquals($sExpected, $this->oBeaut->get());
    }
    /**
    * Bug from Daniel Convissor, 2005-06-20
    * Switch statements aren't coming out right.  I, and most PEAR developers
    * I've asked, partial to them looking like this:
    * <code>
    * switch ($subId) {
    *   case "myevents";
    *       $myeventsOn = "on";
    *       break;
    *   case "publicevents";
    *       $publiceventsOn = "on";
    *       break;
    * }
    * </code>
    */
    function testBugConvissor_2005_06_20() 
    {
        $this->oBeaut->addFilter("Pear");
        $sText = <<<SCRIPT
<?php
switch (\$subId) {
case "myevents":
\$myeventsOn = "on";
break;
case "publicevents":
\$publiceventsOn = "on";
break;
}
?>
SCRIPT;
        $this->setText($sText);
        $sExpected = <<<SCRIPT
<?php
switch (\$subId) {
    case "myevents":
        \$myeventsOn = "on";
        break;

    case "publicevents":
        \$publiceventsOn = "on";
        break;
}
?>
SCRIPT;
        $this->assertEquals($sExpected, $this->oBeaut->get());
    }
    function testBugJustinh_2005_07_26() 
    {
        $sText = <<<SCRIPT
<?php
switch (\$var) {
case 1:
print "hi";
break;
case 2:
default:
break;
}
?>
SCRIPT;
        $this->setText($sText);
        $sExpected = <<<SCRIPT
<?php
switch (\$var) {
    case 1:
        print "hi";
    break;
    case 2:
    default:
    break;
}
?>
SCRIPT;
        $this->assertEquals($sExpected, $this->oBeaut->get());
    }
    function testBugjuancarlos2005_09_13() {
        $this->oBeaut->addFilter("ArrayNested");
        $this->oBeaut->addFilter('IndentStyles',array('style'=>'allman'));
        $sText = <<<SCRIPT
<?php include_once ("turnos.conf.php")
?>
SCRIPT;
        $this->setText($sText);
        $sExpected = <<<SCRIPT
<?php include_once ("turnos.conf.php")
?>
SCRIPT;
        $this->assertEquals($sExpected, $this->oBeaut->get());
    }
    function testBug5711() {
        $this->oBeaut->addFilter("Pear");
        $sText = <<<SCRIPT
<?php

class CampaignManagerConfig {
    
    const BLOCKSIZE_ALL = 9999999;
    
    public static function getStagingUrl(\$liveUrl) {
        return true;
    }
        
}

?>
SCRIPT;
        $this->setText($sText);
        $sExpected = <<<SCRIPT
<?php
class CampaignManagerConfig
{
    const BLOCKSIZE_ALL = 9999999;
    public static function getStagingUrl(\$liveUrl) 
    {
        return true;
    }
}
?>
SCRIPT;
        $this->assertEquals($sExpected, $this->oBeaut->get());
    }
    function testBug6237() {
        $this->oBeaut->addFilter("Pear");
        $sText = <<<SCRIPT
<?php
\$_SESSION["test\$i"];
\$_SESSION["test_\$i"];
\$_SESSION['test_\$i'];
?>
SCRIPT;
        $this->setText($sText);
        $sExpected = <<<SCRIPT
<?php
\$_SESSION["test\$i"];
\$_SESSION["test_\$i"];
\$_SESSION['test_\$i'];
?>
SCRIPT;
        $this->assertEquals($sExpected, $this->oBeaut->get());
    }
    /**
    * according to: http://pear.php.net/manual/en/standards.control.php
    * control strutures should be indented in K&R style
    *
    * if (<cond>) {
    * <body>
    *   }
    *   however, are getting indented in Allman style
    * all control structures are affected.
    */
    function testBug7347() {
        $this->oBeaut->addFilter("Pear");
        $sText = <<<SCRIPT
<?php
class Foo { 
    public function __construct() {
        if(\$foo && \$bar) { echo "FUBAR"; }
    }
}
?>
SCRIPT;
        $this->setText($sText);
        $sExpected = <<<SCRIPT
<?php
class Foo
{
    public function __construct() 
    {
        if (\$foo && \$bar) {
            echo "FUBAR";
        }
    }
}
?>
SCRIPT;
        $this->assertEquals($sExpected, $this->oBeaut->get());
    }
    /**
    * Bad code to detect tokens
    */
    
    function testInternal2() {
        $this->assertTrue(array_key_exists(T_COMMENT,$this->oBeaut->aTokenFunctions));
    }
    /**
    * Adding a comment after a case statement in a switch causes 
    * the indenting to be wrong.
    */
    function testBug7759() {
        $sText = <<<SCRIPT
<?php
echo 0;
switch(1) {
case 1:
case 5: // 5
echo 1;
break;
case 2: //2
echo "something";
echo "something";
case 3: /*3 */ /* 3? */
case 4: 
default:
echo '2';
break;
}
echo 1;
?>
SCRIPT;

$this->setText($sText);

        $sExpected = <<<SCRIPT
<?php
echo 0;
switch (1) {
    case 1:
    case 5: // 5
        echo 1;
    break;
    case 2: //2
        echo "something";
        echo "something";
    case 3: /*3 */ /* 3? */
    case 4:
    default:
        echo '2';
    break;
}
echo 1;
?>
SCRIPT;
        $this->assertEquals($sExpected, $this->oBeaut->get());        
    }
    function testBug7818() {
        $this->oBeaut->startLog();        
        $sText=<<<SCRIPT
<?php
\$field->createElement(\$form, \$this->_table->{\$field->id}, \$defaults);
?>
SCRIPT;
$this->setText($sText);

        $sExpected = <<<SCRIPT
<?php
\$field->createElement(\$form, \$this->_table->{\$field->id}, \$defaults);
?>
SCRIPT;
    $this->assertEquals($sExpected, $this->oBeaut->get());    
    }
    /**
    * Will be great if you can rewrite T_OPEN_TAG_WITH_ECHO in the default
    filter, specially "<?=" because it will be removed in
    PHP6.
    */
    function testBug7854() {
        $this->oBeaut->addFilter("Pear");
        $sText=<<<SCRIPT
<?= \$var ?>
SCRIPT;
$this->setText($sText);

        $sExpected = <<<SCRIPT
<?php echo \$var ?>
SCRIPT;
    $this->assertEquals($sExpected, $this->oBeaut->get());    
    }
}
$suite = new PHPUnit_TestSuite('Beautifier_Bugs');
$result = PHPUnit::run($suite);
echo $result->toString();
?>