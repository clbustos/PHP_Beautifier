@echo off
SET SCRIPT=j:\php\pear\scripts
del *.tgz
j:\php\php.exe -C -d output_buffering=1 -f %SCRIPT%\pear.in -- package j:/sites/PHP_Beautifier/package.xml
j:\php\php.exe -C -d output_buffering=1 -f %SCRIPT%\pear.in -- uninstall PHP_Beautifier
j:\php\php.exe -C -d output_buffering=1 -f %SCRIPT%\pear.in -- install -f PHP_Beautifier-0.0.8.tgz
@echo Ok
