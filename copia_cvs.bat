SET DESTINO=I:\cvs\PHP_Beautifier
xcopy /S /Y *.php  %DESTINO%
xcopy /S /Y tutorials\*  %DESTINO%\tutorials\
xcopy /S /Y scripts\* %DESTINO%\scripts\
xcopy /S /Y licenses\* %DESTINO%\licenses\
copy package.xml %DESTINO%\