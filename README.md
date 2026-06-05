# Copona

Copona is an open-source PHP digital e-commerce platform inspired by and based on OpenCart (http://www.opencart.com).

A good alternative to OpenCart, WooCommerce, and PrestaShop. Read our Wiki for more information on changes and advantages over competitors.

Copona is in development mode — please use it, test it, and post issues, bugs, or **feature requests** at https://github.com/Copona/copona/issues. Our team will be happy to assist!


## Requirements
* MySQL >= 5.6
* PHP >= 8.3
* Composer [https://getcomposer.org/](https://getcomposer.org/)

## Get started
`composer create-project copona/copona --stability=dev`

`cd copona && php copona install`

## Installation
* Getting project files
    * With Git (recommended)
        * Install Git [guide](http://rogerdudler.github.io/git-guide)
        * Install Composer [guide](https://getcomposer.org/doc/01-basic-usage.md#installing-dependencies)
        * Navigate to your webroot, for example:
            * `cd /var/www/public_html`
        * `git clone https://github.com/Copona/copona.git .`
        * `git config user.name "Your Name"`
        * `git config user.email youremail@yourdomain.org`
        * `git config core.fileMode false`
        * `composer install`
        * Open the installer at `http://domain/install`
    * Using manual download:
        * [Click here to download the master branch](https://github.com/copona/copona/archive/master.zip)
* Prepared environment
    * With Docker
        * Install [Docker](https://docs.docker.com/engine/installation/) and [Docker Compose](https://docs.docker.com/compose/install/)
        * Duplicate `.env.example` to `.env` and configure the file
        * Execute `docker-compose up -d`
        * Access bash via `docker-compose exec web bash` and execute:
            * `cd /app && composer install`
            * `cd /app && php vendor/bin/phinx migrate`
    * Manual install
        * Install a web server: Apache, IIS, etc.
        * Install PHP and MySQL
        * Install Composer [https://getcomposer.org/](https://getcomposer.org/)
        * From the command prompt, execute:
            * `composer install`
* Navigate to your web address: `http://domain-OR-IPaddress/` or `http://domain-OR-IPaddress/subfolder-where-you-cloned`
* Execute migration: `php vendor/bin/phinx migrate`
* If all requirements have been met, fill in the form and enjoy!

## Update
* If you installed Copona with Git (recommended), go to the folder where Copona is installed:
  * If you have not edited any files locally:
    * `git pull`
  * If you have edited files locally — you are a developer, you will know what to do!
  * Check the site; if there are problems, post them online, or you can always revert to the previous version.
* Run Composer install:
  * `composer install`
* Run database migration:
  * `php vendor/bin/phinx migrate` (https://github.com/copona/copona/wiki/Migration-Phinx)


## TODO

* Put into migrations:

```sql
ALTER TABLE `cp_url_alias`
ADD INDEX `query_language_id` (`query`, `language_id`);
```
