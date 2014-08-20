<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */
/**
 * Filter Long Array Nested: Indent long array structures only
 *
 * PHP version 5
 *
 * LICENSE: This source file is subject to version 3.0 of the PHP license
 * that is available through the world-wide-web at the following URI:
 * http://www.php.net/license/3_0.txt.  If you did not receive a copy of
 * the PHP License and are unable to obtain it through the web, please
 * send a note to license@php.net so we can mail you a copy immediately.
 *
 * @category   PHP
 * @package    PHP_Beautifier
 * @subpackage Filter
 * @author     Elod Csirmaz <https://github.com/csirmaz>
 * @copyright  2014 Elod Csirmaz, based on ArrayNested by Claudio Bustos
 * @license    http://www.php.net/license/3_0.txt  PHP License 3.0
 * @version    CVS: $Id:$
 * @link       http://pear.php.net/package/PHP_Beautifier
 * @link       http://beautifyphp.sourceforge.net
 */
/**
 * Filter Long Array Nested: Indent long array structures only
 *
 * Break array()s into multiple lines that, on a single line, would be longer
 * than a given limit. This limit defaults to 50, and can be specified using
 * the 'maxlen' parameter:
 *
 * --filters="LongArrayNested(maxlen=20)"
 *
 * @category   PHP
 * @package    PHP_Beautifier
 * @subpackage Filter
 * @author     Elod Csirmaz <https://github.com/csirmaz>
 * @copyright  2014 Elod Csirmaz, based on ArrayNested by Claudio Bustos
 * @license    http://www.php.net/license/3_0.txt  PHP License 3.0
 * @version    CVS: $Id:$
 * @link       http://pear.php.net/package/PHP_Beautifier
 * @link       http://beautifyphp.sourceforge.net
 */
/**
 * Based on:
 * Filter Array Nested: Indent the array structures
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
 *
 * @category   PHP
 * @package    PHP_Beautifier
 * @subpackage Filter
 * @author     Claudio Bustos <cdx@users.sourceforge.com>
 * @copyright  2004-2010 Claudio Bustos
 * @license    http://www.php.net/license/3_0.txt  PHP License 3.0
 * @version    Release: @package_version@
 * @link       http://pear.php.net/package/PHP_Beautifier
 * @link       http://beautifyphp.sourceforge.net
 */
class PHP_Beautifier_Filter_LongArrayNested extends PHP_Beautifier_Filter
{
    protected $aSettings = array('maxlen' => '50');
    var $stack = array();
    protected $sDescription = 'Break array()s into multiple lines that, on a single line, would be longer than a given limit';
    public function __construct(PHP_Beautifier $oBeaut, $aSettings = array())
    {
        parent::__construct($oBeaut, $aSettings);
        $this->addSettingDefinition('maxlen', 'text', 'Break array()s into multiple lines above this length');
    }
    /**
     * t_parenthesis_open
     *
     * @param mixed $sTag The tag to be procesed
     *
     * @access public
     * @return void
     */
    public function t_parenthesis_open($sTag)
    {
        $this->oBeaut->add($sTag);
        if ($this->oBeaut->getControlParenthesis() == T_ARRAY) {
            $this->oBeaut->addNewLine();
            $this->oBeaut->incIndent();
            $this->oBeaut->addIndent();
            array_push($this->stack, count($this->oBeaut->aOut));
        }
    }
    /**
     * t_parenthesis_close
     *
     * @param mixed $sTag The tag to be procesed
     *
     * @access public
     * @return void
     */
    public function t_parenthesis_close($sTag)
    {
        $isShortArray = false;
        if ($this->oBeaut->getControlParenthesis() == T_ARRAY) {
            $begCount = array_pop($this->stack);
            // Check how long the array is, without whitespace
            $arraystr = '';
            for ($i = $begCount; $i < count($this->oBeaut->aOut); $i++) {
                $arraystr.= $this->oBeaut->aOut[$i];
            }
            $arraystr = preg_replace('/\s/', '', $arraystr);
            // If it is too short, remove whitespace we added before.
            // The aOut[] elements are:
            //        -2: newline
            //        -1: indent
            // $begCount: FIRST ELEMENT
            //        +1: comma
            //        +2: newline
            //        +3: indent
            //        +4: SECOND ELEMENT
            if (strlen($arraystr) < $this->aSettings['maxlen']) {
                $isShortArray = true;
                $begCount2 = $begCount - 2;
                if ($begCount2 < 0) {
                    $begCount2 = 0;
                }
                for ($i = $begCount2; $i < count($this->oBeaut->aOut); $i++) {
                    $new = $this->oBeaut->aOut[$i];
                    $new = preg_replace('/^\r?\n$/', '', $new);
                    $new = preg_replace('/^[ \t]+$/', ($i < $begCount ? '' : ' '), $new);
                    $this->oBeaut->aOut[$i] = $new;
                }
            }
            $this->oBeaut->decIndent();
            if ($this->oBeaut->getPreviousTokenContent() != '(' && !$isShortArray) {
                $this->oBeaut->addNewLine();
                $this->oBeaut->addIndent();
            }
            $this->oBeaut->add($sTag . ' ');
        } else {
            $this->oBeaut->add($sTag . ' ');
        }
    }
    /**
     * t_comma
     *
     * @param mixed $sTag The tag to be procesed
     *
     * @access public
     * @return void
     */
    public function t_comma($sTag)
    {
        if ($this->oBeaut->getControlParenthesis() != T_ARRAY) {
            $this->oBeaut->add($sTag . ' ');
        } else {
            $this->oBeaut->removeWhitespace();
            $this->oBeaut->add($sTag);
            $this->oBeaut->addNewLine();
            $this->oBeaut->addIndent();
        }
    }
}
?>
