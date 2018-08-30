# What is this
This is the [Git repository](https://en.wikipedia.org/wiki/Git) for the code that runs the [Fleming Fund website](http://www.flemingfund.org)

# Tech overview
* Content Management System (CMS): WordPress
* Hosting: AWS
* CI: Travis CI

# Zero-to-hero

## Requirements
* Get access to the Fleming Fund Zoho chamber

* Install Git for Windows (https://git-scm.com/download/win)  
  (optional extra) Install your Git GUI of choice

* Install NPM + Node (https://nodejs.org/en/download/)

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

* Create a `php.ini` file where you just installed PHP.  
  PHP ships with a `php.ini-development` file - copy this to `php.ini`  
  Make the following changes to your `php.ini` file:
  * Un-comment `extension_dir = "ext"`
  * Un-comment `extension=curl`
  * Un-comment `extension=gd2`
  * Un-comment `extension=intl`
  * Un-comment `extension=mbstring`
  * Un-comment `extension=mysqli`
  * Un-comment `extension=openssl`
  * Change the `variables_order` line to be `variables_order = "EGPCS"`

## First time setup

* Check out the code  
  `git clone git@github.com:Softwire/fleming-fund-website.git`

* Make a copy the `.credentials-template` folder called `.credentials`.

* Fill in `.credentials/aws-credentials-backup-download.json` with vlaues from Zoho.

* Fill in `.credentials/youtube-api-key.json` with the value from Zoho.

* Fill in `DB_USER` and `DB_PASSWORD` with your root database user credentials.

* Get a copy of the paid-for plugins we use off X:\VisibleToEmployees\Customers\Fleming Fund and put them in dependencies/plugins.
(the names should match `.wp-install.yml`)

* Run `one-click-install.sh`. This will:
  * `npm install`
  * Install wordpress + plugins
  * Fetch and restore a copy of the database and uploads folder from S3.
    **THIS WILL DELETE ANY EXISTING WORDPRESS DATABASE**

## Running the website

* Run these two shell scripts to build and serve the website:
  * `dev--build-and-watch.sh`  
  This uses Webpack to compile SCSS etc  
  It watches for changed files and re-compiles automatically.

  * `dev--run-php-server.sh`  
  This runs a simple PHP server in the right folder.  
  **Note:** this file must be run from an existing command prompt  
  e.g. by running `./dev--run-php-server.sh`  
  For some reason, if you just double-click on this shell script in Windows Explorer, it doesn't know where your `php.ini` file is :-(

**Be careful not to commit any secrets to git -- this is a public repository.**


## Using an AWS database

To use the live/staging/test database edit `.credentials/set-local-credentials.sh`, commenting out your local database and
uncommenting a different one. Get the password from Zoho.


# Custom Post Types and Custom Field Groups

* Custom Post Types (CPTs) and Custom Field Groups (CFGs) should be stored in the code repository, not the database.

* For the time being, we have at most one CFG for each CPT, and they are not shared between CPTs.

* CPTs should be defined by creating a [post type name].php file in the src/fleming-theme/custom-post-types directory. This is mostly boilerplate that can be copied from an existing one.

* CFGs can be easily created in the Wordpress admin interface, but should be exported as .json files (see Custom Fields -> Tools) and stored in src/fleming-theme/custom-post-types as [field group name].php, and the CPT should then be deleted from the database.

* Both CPTs and CFGs should be loaded via load-custom-post-types-and-acf-fields.php in src/fleming-theme. Just add an include and/or load_acf_fields call inside the eponymous function similar to the ones already there.

* Be careful about underscores and hyphens when naming custom components and files.

* CFGs for pages should exist in page-[page name].json in the same folder.