<html>
<head>
<title>PHP_Beautifier</title>
	<style type="text/css" media="screen">
		@import url( http://apsique.virtuabyte.cl/php/wp-layout.css );
	</style>

</head>
<style>
pre {
	padding:5px;
	border-bottom: double 1px #9166CC;
	border-left: solid 1px #9166CC;
	border-right: solid 1px #63468c;
	border-top: solid 1px #63468c;
	background: #C4ACE6;
	width:1%;
	color:black;
}
</style>
<body>
<h1 id="header">PHP_Beautifier</h1>
<h2>Purpose</h2>
<p>This program reformat and beautify PHP source code files automatically. The program is Open Source and distributed under the terms of PHP Licence. It is written in PHP 5 and has a command line tool.</p>
<h2>Package</h2>
<p><b>Pear: 'Stable' release. </b> <a href='http://pear.php.net/package/PHP_Beautifier'>http://pear.php.net/package/PHP_Beautifier</a></p>
<p><b>Local:The latest, but maybe broken. </b> <?php
    function getUrl($sFile) 
    {
        return 'http://'.$_SERVER['SERVER_NAME']."/".$sFile;
    }
    $oDir = new DirectoryIterator("./");
    for (;$oDir->valid();$oDir->next()) {
        $sFile = $oDir->getFileName();
        if ($oDir->isFile() and preg_match("/PHP_Beautifier-.*\.tgz/", $sFile)) {
            $sTgz = getUrl($sFile);
            break;
        } else {
            $sTgz = '<b>Error</b>';
        }
    }
    unset($oDir);
?>  <a href="<?php
    echo $sTgz
?>"><?php
    echo $sTgz
?></a></p>
<p>Install with</p>
<pre>pear install -f <?php
    echo $sTgz
?></pre>
<h2>Demostration</h2>
<p>Test the code on </p>
<p><a href='http://clbustos.dotgeek.org/demo/'>http://clbustos.dotgeek.org/demo/</a></p>
<h2>Source code</h2>
<p><b>CVS:</b> <a href="http://cvs.sourceforge.net/viewcvs.py/beautifyphp/PHP_Beautifier/">http://cvs.sourceforge.net/viewcvs.py/beautifyphp/PHP_Beautifier/</a></p>
<p>Beautified with</p>
<pre>php_beautifier -l "Pear() ArrayNested" -r *.php ./phps/</pre>
<ul>
<?php
    function recurse($it) 
    {
        echo '<ul>';
        for (;$it->valid();$it->next()) {
            if ($it->isDir() && !$it->isDot()) {
                printf('<li class="dir">%s</li>', $it->current());
                if ($it->hasChildren()) {
                    $bleh = $it->getChildren();
                    echo '<ul>'.recurse($bleh) .'</ul>';
                }
            } elseif ($it->isFile() and stripos($it->getFileName() , ".phps")) {
                echo '<li class="file"><a href="'.getUrl($it->getPath() ."/".$it->getFileName()) .'">'.$it->current() .'</a> ('.$it->getSize() .' Bytes)</li>';
            }
        }
        echo '</ul>';
    }
    recurse(new RecursiveDirectoryIterator('phps/'));
?>
</ul>
<h2>Documentation</h2>
<h3>API</h3>
<p><a href='docs/HTMLframesConverter/index.html'>Documentation made by PhpDocumentor</a></p>
<h3>UML</h3>
<ul>
<?
$oDir = new DirectoryIterator("./uml");
    for (;$oDir->valid();$oDir->next()) {
        $sFile = $oDir->getFileName();
        if ($oDir->isFile()) {
            $sFile = getUrl($sFile);
            ?><li><a href='uml/<?php echo $oDir->getFileName(); ?>'><?php echo $oDir->getFileName(); ?></a></li><?
        }
    }
    unset($oDir);
    ?>
    </ul>
<h2>Example</h2>
<p>A script that beautify itself: <a href='example.phps'>This file</a> generate this <a href='example_output.phps'>output</a></p>
<h2>Banners</h2>
<p>If you use PHP_Beautifier on your site, use one of the following banners</p>
<p><img src='images/buttons/button-php_beautifier.png' /> 
<br />
<code>
&lt;a href='http://clbustos.dotgeek.org/'&gt;&lt;img src='http://clbustos.dotgeek.org/images/buttons/button-php_beautifier.png' /&gt;&lt;/a&gt;
</code>
</p>
<hr />
<a href="http://www.jedit.org">
<img src="http://www.jedit.org/made-with-jedit-4.png"
alt="Developed with jEdit"
border="0" width="96" height="36">
</a>
</p>
</body>
</html>