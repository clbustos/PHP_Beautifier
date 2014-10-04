<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */
/**
 * Add space before operator+assignments
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
 * @author     Ken Guest <kguest@php.net>
 * @copyright  2014 Ken Guest
 * @license    http://www.php.net/license/3_0.txt  PHP License 3.0
 * @version    1.0
 * @link       http://pear.php.net/package/PHP_Beautifier
 * @link       http://beautifyphp.sourceforge.net
 */
/**
 * Add space before before and after type casting operations.
 *
 * @category   PHP
 * @package    PHP_Beautifier
 * @subpackage Filter
 * @author     Ken Guest <kguest@php.net>
 * @copyright  2014 Ken Guest
 * @license    http://www.php.net/license/3_0.txt  PHP License 3.0
 * @version    1.0
 * @link       http://pear.php.net/package/PHP_Beautifier
 * @link       http://beautifyphp.sourceforge.net
 */
class PHP_Beautifier_Filter_SpaceCasts extends PHP_Beautifier_Filter
{
    protected $sDescription = 'Ensure there are spaces around cast operations';

    /**
     * t_array_cast
     *
     * @param mixed $sTag The tag to be processed
     *
     * @access public
     * @return void
     */
    public function t_array_cast($sTag)
    {
        $this->oBeaut->removeWhitespace();
        $this->oBeaut->add(' ' . $sTag . ' ');
    }

    /**
     * t_bool_cast
     *
     * @param mixed $sTag The tag to be processed
     *
     * @access public
     * @return void
     */
    public function t_bool_cast($sTag)
    {
        $this->oBeaut->removeWhitespace();
        $this->oBeaut->add(' ' . $sTag . ' ');
    }

    /**
     * t_double_cast
     *
     * @param mixed $sTag The tag to be processed
     *
     * @access public
     * @return void
     */
    public function t_double_cast($sTag)
    {
        $this->oBeaut->removeWhitespace();
        $this->oBeaut->add(' ' . $sTag . ' ');
    }

    /**
     * t_int_cast
     *
     * @param mixed $sTag The tag to be processed
     *
     * @access public
     * @return void
     */
    public function t_int_cast($sTag)
    {
        $this->oBeaut->removeWhitespace();
        $this->oBeaut->add(' ' . $sTag . ' ');
    }

    /**
     * t_object_cast
     *
     * @param mixed $sTag The tag to be processed
     *
     * @access public
     * @return void
     */
    public function t_object_cast($sTag)
    {
        $this->oBeaut->removeWhitespace();
        $this->oBeaut->add(' ' . $sTag . ' ');
    }

    /**
     * t_string_cast
     *
     * @param mixed $sTag The tag to be processed
     *
     * @access public
     * @return void
     */
    public function t_string_cast($sTag)
    {
        $this->oBeaut->removeWhitespace();
        $this->oBeaut->add(' ' . $sTag . ' ');
    }

    /**
     * t_unset_cast
     *
     * @param mixed $sTag The tag to be processed
     *
     * @access public
     * @return void
     */
    public function t_unset_cast($sTag)
    {
        $this->oBeaut->removeWhitespace();
        $this->oBeaut->add(' ' . $sTag . ' ');
    }

}
?>
