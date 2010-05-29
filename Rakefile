require 'rake'
task :test do
  files=Dir.glob("tests/*.phpt")
  passed=0
  failed=0
  files.each {|f|
    if system "php #{f}"
      passed+=1
    else
      failed+=1
    end
  }
  puts "Passed:#{passed}, Failed:#{failed}"
end

task :install do
    system "pear install package2.xml"
end

task :uninstall do
    system "pear uninstall PHP_Beautifier"
end

task :reinstall => [:uninstall, :install] do
end
