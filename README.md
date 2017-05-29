# Copona

Copona is open source PHP digital e-commerce platform inspired and based on Opecnart http://www.opencart.com.

Copona is in DEV mode so, please, use it and test it. Post issues, bugs or **feature requests** here https://github.com/Copona/copona/issues. Our team will be happy to assist!


## Requirements
* MySQL >= 5.3
* PHP >= 5.6
* Composer [https://getcomposer.org/](https://getcomposer.org/)

## Installation
* Getting project files
    * With Git (recommended)
        * install git [guide](http://rogerdudler.github.io/git-guide)
		* install composer [guide](https://getcomposer.org/doc/01-basic-usage.md#installing-dependencies)
        * execute commands: 
		* cd to webroot, like:
		    * `cd /var/www/public_html`
        * `git clone https://github.com/Copona/copona.git .`
        * `git config user.name "Your Name"`
        * `git config user.email youremail@yourdomain.org`
        * `git config core.fileMode false`
		* `composer install`
		* open installation `http://domain/install`
		* After successful installation, run DB migration from webroot folder: 
		    * `php vendor/bin/phinx migrate`
    * Download files
        * [Click here to download master branch](https://github.com/copona/copona/archive/master.zip)
* Prepared environment
    * With Docker
        * Install [Docker](https://docs.docker.com/engine/installation/) and [Docker Compose](https://docs.docker.com/compose/install/)
        * Duplicate `.env.example` to `.env` and configure file
        * Execute `docker-compose up -d`
        * From the command prompt, acess bash `docker-compose exec web bash` and execute:
            * Execute composer `cd /app && composer install`
            * Execute migration `cd /app && php vendor/bin/phinx migrate`
    * Manual install
        * Install WEB server Apache, IIS, etc.
        * Install PHP and MySQL 
        * Install Composer [https://getcomposer.org/](https://getcomposer.org/)
        * From the command prompt, execute:
            * Execute composer `composer install`
* navigate to WEB address `http://domain-OR-IPaddress/` or `http://domain-OR-IPaddress/subfolder-where-you-cloned`
	* Execute migration `php vendor/bin/phinx migrate`
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
