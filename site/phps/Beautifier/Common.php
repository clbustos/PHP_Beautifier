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
    * Common.class.php
    * Common methods for PHP_Beautifier
    * @package PHP_Beautifier
    */
    /**
    * PHP_Beautifier_Common
    *
    * Common methods for PHP_Beautifier, almost file management.
    * All the methods are static
    *
    * @package PHP_Beautifier
    */
    class PHP_Beautifier_Common {
        /**
        * Normalize reference to directories
        * @param  string path to directory
        * @return string normalized path to directory
        */
        public static function normalizeDir($sDir) {
            $sDir = str_replace(DIRECTORY_SEPARATOR, '/', $sDir);
            if (substr($sDir, -1) != '/') {
                $sDir.= '/';
            }
            return $sDir;
        }
        /**
        * Search, inside a dir, for a file pattern, using regular expresion
        * Example:
        *
        * <code>PHP_Beautifier_Common::getFilesByPattern('.','*.php',true);</code>
        * Search recursively for all the files with php extensions
        * in the current dir
        * @param    string  path to a dir
        * @param    string  file pattern
        * @param    bool    recursive?
        * @return   array   path to files
        */
        public static function getFilesByPattern($sDir, $sFilePattern, $bRecursive = false) {
            if (substr($sDir, -1) == '/') {
                $sDir = substr($sDir, 0, -1);
            }
            $dh = @opendir($sDir);
            if (!$dh) {
                throw (new Exception("Cannot open directory '$sDir'"));
            }
            $matches = array();
            while ($entry = @readdir($dh)) {
                if ($entry == '.' or $entry == '..') {
                    continue;
                } elseif (is_dir($sDir.'/'.$entry) and $bRecursive) {
                    $matches = array_merge($matches, PHP_Beautifier_Common::getFilesByPattern($sDir.'/'.$entry, $sFilePattern, $bRecursive));
                } elseif (preg_match("/".$sFilePattern."$/", $entry)) {
                    $matches[] = $sDir."/".$entry;
                }
            }
            if (!$matches) {
                PHP_Beautifier_Common::getLog()->log("$sDir/$sFilePattern pattern don't match any file", PEAR_LOG_DEBUG);
            }
            return $matches;
        }
        /**
        * Create a dir for a file path
        * @param    string  file path
        * @return   bool
        * @throws   Exception
        */
        public static function createDir($sFile) {
            $sDir = dirname($sFile);
            if (file_exists($sDir)) {
                return true;
            } else {
                $aPaths = explode('/', $sDir);
                $sCurrentPath = '';
                foreach($aPaths as $sPartialPath) {
                    $sCurrentPath.= $sPartialPath.'/';
                    if (file_exists($sCurrentPath)) {
                        continue;
                    } else {
                        if (!@mkdir($sCurrentPath)) {
                            throw (new Exception("Can't create directory '$sCurrentPath'"));
                        }
                    }
                }
            }
            return true;
        }
        /**
        * Return an array with the paths to save for an array of files
        * @param    array  Array of files (input)
        * @param    string Init path
        * @return   array  Array of files (output)
        */
        public static function getSavePath($aFiles, $sPath = './') {
            $sPath = PHP_Beautifier_Common::normalizeDir($sPath);
            // get the lowest denominator..
            $sPrevious = '';
            $iCut = 0;
            foreach($aFiles as $i=>$sFile) {
                $sFile = preg_replace("/^.*?#/", '', $sFile);
                $aFiles[$i] = $sFile;
                if (!$sPrevious) {
                    $sPrevious = dirname($sFile);
                    continue;
                }
                for ($x = 0;$x<strlen($sPrevious);$x++) {
                    $sDir = dirname($sFile);
                    if (strlen($sDir) <$x+1) {
                        $sPrevious = '';
                        break(2);
                    } elseif ($sDir{$x} != $sPrevious{$x}) {
                        $sPrevious = substr($sDir, 0, $x);
                        continue;
                    }
                }
            }
            $iCut = strlen($sPrevious);
            $aPathsOut = array();
            foreach($aFiles as $sFile) {
                $sFileOut = preg_replace("/^(\w:\/|\.\/|\/)/", "", substr($sFile, $iCut));
                $aPathsOut[] = $sPath.$sFileOut;
            }
            return $aPathsOut;
        }
        /**
        * Search, inside a dir, for a file pattern using glob(* and ?)
        * @param    string  path
        * @param    bool    recursive
        * @return   array   path to files
        */
        public static function getFilesByGlob($sPath, $bRecursive = false) {
            if (!$bRecursive) {
                return glob($sPath);
            } else {
                $sDir = (dirname($sPath)) ? realpath(dirname($sPath)) : realpath('./');
                $sGlob = basename($sPath);
                $dh = @opendir($sDir);
                if (!$dh) {
                    throw (new Exception("Cannot open directory '$sPath'"));
                }
                $aMatches = glob($sDir.'/'.$sGlob);
                while ($entry = @readdir($dh)) {
                    if ($entry == '.' or $entry == '..') {
                        continue;
                    } elseif (is_dir($sDir.'/'.$entry)) {
                        $aMatches = array_merge($aMatches, PHP_Beautifier_Common::getFilesByGlob($sDir.'/'.$entry.'/'.$sGlob, true));
                    }
                }
                return $aMatches;
            }
        }
        /**
        * Get a {@link Log_composite} object for PHP_Beautifier
        * Always return the same object (Singleton pattern)
        * @return Log_composite
        */
        public static function getLog() {
            return Log::singleton('composite', 'PHP_Beautifier');
        }
        /**
        * Transform whitespaces into its representation
        * So, tabs becomes \t, newline \n and feed \r
        * Useful for log
        * @param string
        * @return string
        */
        function wsToString($sText) {
            // ArrayNested->off();
            return str_replace(array(
                "\r",
                "\n",
                "\t"
            ) , array(
                '\r',
                '\n',
                '\t'
            ) , $sText);
            // ArrayNested->on();
            
        }
    }
    // Interfaces
    
    /**
    * Interface for PHP_Beautifier and subclasses.
    * Created to made a 'legal' Decorator implementation
    *
    * @package PHP_Beautifier
    */
    interface PHP_Beautifier_Interface {
        /**
        * Process the file(s) or string
        */
        public function process();
        /**
        * Show on screen the output
        */
        public function show();
        /**
        * Get the output on a string
        * @return string
        */
        public function get();
        /**
        * Save the output to a file
        * @param string path to file
        */
        public function save($sFile = null);
    }
?>