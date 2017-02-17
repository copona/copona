# Copona

Copona is open source PHP digital e-commerce platform inspired and based on Opecnart http://www.opencart.com.

Copona is in DEV mode so, please, use it and test it. Post issues, bugs or **feature requests** here https://github.com/Copona/copona/issues. Our team will be happy to assist!


## Requirements
* MySQL 5.3
* PHP >=5.6
* Composer [https://getcomposer.org/](https://getcomposer.org/)

## Installation with Git (recommended)
* Install WEB server Apache, IIS, etc.
  * If You have problem, please, post the issues here: https://github.com/Copona/copona/issues 
* Install PHP and MySQL 
* Install Composer
* install git
 * Ubuntu, Debian
   * `apt-get install git`
   * Prerequisites
     * `apt-get install php-curl`
     * `apt-get install php-zip`
 * CentOS, RedHat
   * `yum install git`
 * Windows
   * go to https://git-scm.com/downloads
    * download Git for Windows
    * execue the installation
    * if you are not sure: choose `next > next > next > ... > next > install`
* open WEB directory where do you want to have Copona
* execute commands: 
 * `git clone https://github.com/Copona/copona.git .`
 * `git config user.name "Your Name"`
 * `git config user.email youremail@yourdomain.org`
 * `git config core.fileMode false`
* From the command prompt, execute:
 * `composer update`
* navigate to WEB address `http://domain-OR-IPaddress/` or `http://domain-OR-IPaddress/subfolder-where-you-cloned`
* If all the requirements have been met, fill the form and enjoy!

## Update
* if you have installed Copona with Git (recommended), then got to the folder where you have Copona:
  * if you have not edited anything in files locally:
    * `git pull`
  * if you have edited something locally - then you are a developer already - you will know, what to do! :)
  * check the site, if there are problems - post them online, or you can always return to the previous version.
* Run Composer install
 * `composer install`
* Run database migration
 * `php vendor/bin/phinx migrate` (https://github.com/copona/copona/wiki/Migration-Phinx)
