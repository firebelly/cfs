# CFS

A Bedrock/Sage site for https://chicagofreedomschool.org

## Basic requirements

You'll need a local copy of Mariadb or Mysql running with Apache for local development. A great guide for getting a Mac up and running for development:

https://getgrav.org/blog/macos-catalina-apache-multiple-php-versions

Install scripts expect the site to be at `cfs.localhost`. You can either edit your `/etc/hosts` file every time you have a new local site to work on with, e.g.:

    127.0.0.1 cfs.localhost

... or set up all `*.localhost` requests to resolve to your local machine. Here's a good guide for this:

https://medium.com/@kharysharpe/automatic-local-domains-setting-up-dnsmasq-for-macos-high-sierra-using-homebrew-caf767157e43

## Set up Apache

Bedrock/Sage sites need to be served from the `web` dir, so a vhost entry would look like this:

    <VirtualHost *:80>
        DocumentRoot "/Users/natebeaty/Firebelly/cfs/web"
        ServerName cfs.localhost
        ErrorLog "/private/var/log/apache2/cfs.localhost-error_log"
    </VirtualHost>

If you're using Sublime Text, and followed the guide above, edit the Apache vhost file with:

`subl /usr/local/etc/httpd/extra/httpd-vhosts.conf`

## Install WordPress, Capistrano, and Composer/Node packages for development and deploys

Install homebrew: https://brew.sh

Install Composer, wp-cli, and rbenv with rbenv-gemset:

`brew install composer wp-cli rbenv ruby-build rbenv-gemset`

Run `sh install.sh` to install all Composer packages, gemsets for Capistrano deploys, and set up the Gulp build system in `app/themes/cfs`.

## Pull a fresh copy of staging or production db

`cap production wpcli:db:pull`

Which will dump the remote db, scp it locally, install the db into what's specified in `.env`, and update all domain references in the db using `wp-cli` (see `config/deploy/production.rb` for domain config changes).

## To develop locally

`cd app/themes/cfs; npx gulp watch` to monitor changes to scss, js and php files. If you view the site at `http://cfs.localhost:3000` you'll get live updates with BrowserSync.

## To deploy

`cap staging deploy`

Will create a new release dir on WebFaction in webapps/cfs_staging/, compile & scp assets, and finally update `webapps/cfs_staging/current` to the new release dir.
