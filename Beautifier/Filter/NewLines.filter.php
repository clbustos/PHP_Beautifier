<?php
    /* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */
    /**
    * New Lines: Add extra new lines after o before specific contents
    *
    * PHP version 5
    *
    * LICENSE: This source file is subject to version 3.0 of the PHP license
    * that is available through the world-wide-web at the following URI:
    * http://www.php.net/license/3_0.txt.  If you did not receive a copy of
    * the PHP License and are unable to obtain it through the web, please
    * send a note to license@php.net so we can mail you a copy immediately.
    * @category   PHP
    * @package PHP_Beautifier
    * @subpackage Filter
    * @author Claudio Bustos <clbustos@dotgeek.org>
    * @copyright  2004-2005 Claudio Bustos
    * @link     http://pear.php.net/package/PHP_Beautifier
    * @link     http://clbustos.dotgeek.org
    * @license    http://www.php.net/license/3_0.txt  PHP License 3.0
    * @since      File available since Release 0.1.2
    * @version    CVS: $Id:$
    */
    /**
    * New Lines: Add new lines after o before specific contents
    * The settings are 'before' and 'after'. As value, use a comma separated
    * list of contents
    *
    * @category   PHP
    * @package PHP_Beautifier
    * @subpackage Filter
    * @author Claudio Bustos <clbustos@dotgeek.org>
    * @copyright  2004-2005 Claudio Bustos
    * @link     http://pear.php.net/package/PHP_Beautifier
    * @link     http://clbustos.dotgeek.org
    * @license    http://www.php.net/license/3_0.txt  PHP License 3.0
    * @version    Release: 0.1.3
    * @since      Class available since Release 0.1.2    
    */
    class PHP_Beautifier_Filter_NewLines extends PHP_Beautifier_Filter {
        protected $aSettings = array(
            'before'=>false,
            'after'=>false
        );
        protected $sDescription = 'Add new lines after o before specific contents';
        private $aBefore=array();
        private $aAfter=array();
        public function __construct(PHP_Beautifier $oBeaut, $aSettings = array()) 
        {
            parent::__construct($oBeaut, $aSettings);
            $this->addSettingDefinition('before', 'text', 'List of contents to put new lines before, separated by commas');
            $this->addSettingDefinition('after', 'text', 'List of contents to put new lines after, separated by commas');
            if(!empty($this->aSettings['before'])) {
                $this->aBefore=explode(',', str_replace(' ', '', $this->aSettings['before']));
            }
            if(!empty($this->aSettings['after'])) {
                $this->aAfter=explode(',',str_replace(' ', '', $this->aSettings['after']));
            }
            $this->oBeaut->setNoDeletePreviousSpaceHack();
        }
        public function __call($sMethod, $aArgs) 
        {
            $sToken=$aArgs[0];
            if(in_array($sToken,$this->aBefore)) {
                $this->oBeaut->addNewLineIndent();
            }
            if(in_array($sToken,$this->aAfter)) {
                $this->oBeaut->setBeforeNewLine($this->oBeaut->sNewLine.'/**ndps**/');
            }
            return PHP_Beautifier_Filter::BYPASS;
        }
    }
?>