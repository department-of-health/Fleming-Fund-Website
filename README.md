# What is this
This is the [Git repository](https://en.wikipedia.org/wiki/Git) for the code that runs the [Fleming Fund website](http://www.flemingfund.org)

# Tech overview
* Content Management System (CMS): WordPress
* Hosting: AWS
* CI: Travis CI

# Zero-to-hero
* Install Git for Windows (https://git-scm.com/download/win)  
  (optional extra) Install your Git GUI of choice

* Install NPM (part of Node.js) (https://nodejs.org/en/download/)

* Install the latest version of MySQL Community (https://dev.mysql.com/downloads/installer)
  * Note: it asks you to login to an Oracle Web Account  
    Ignore this, just click "No thanks, just start my download."  
    When I ran it, the installer was quite flaky and I had to try it a couple of times.
  * Choose "Custom" install type and choose "Server" and "Workbench"
  * Choose "Use Legacy Authentication Method"  
    (at the time of writing) MySQL Workbench (the GUI) is incompatible with the newer authentication mode (!)
  * Set a root password (for your dev machine, this can be anything, or blank)
  * Use defaults for all other options

* Install the latest version of PHP (https://windows.php.net/download)  
  Use the x64 thread-safe version
  * Extract the zip file somewhere sensible (e.g. C:\Program Files\PHP)
  * Add the folder containing `php.exe` to your PATH environment variable  
    Hint: [Rapid Environment Editor](https://www.rapidee.com/en/download) is a really useful tool for doing this

* Check out this code  
  `git clone git@github.com:Softwire/fleming-fund-website.git`

* Run these commands in a shell (e.g. Git bash):
```
npm install
npm install wp-install -g
wp-install
```

* Run these two shell scripts to server the website:
  * `dev--build-and-watch.sh`  
  This uses Webpack to compile SCSS etc  
  It watches for changed files and re-compiles automatically.
  * `dev--run-php-server.sh`  
  This runs a simple PHP server in the right folder.

Note: you can just double-click on these shell scripts in Windows Explorer to open them in Git Bash - they each close automatically when you press Ctrl-C

