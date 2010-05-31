require 'rake'
require 'fileutils'
task :test do
  Dir.chdir('tests')
  system "phpunit ."
end

task :install do
    system "pear install package2.xml"
end

task :uninstall do
    system "pear uninstall PHP_Beautifier"
end

task :reinstall => [:uninstall, :install] do
end

task :doc do
    system "phpdoc -c Doc_PHP_Beautifier.ini"
end

task :package do
  FileUtils.mkdir "pkg"  
  system "pear package package2.xml"
  system "mv PHP_Beautifier*.tgz pkg"
end
